<?php

namespace App\Console\Commands;

use App\Models\Team;
use Illuminate\Console\Command;

class GetTeamsDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:getTeamDetails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and update team details from external source';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fetch team details...');

        $this->getTeamsDetails();

        $this->info('All team details have been processed successfully.');
    }

    /**
     * Fetch teams with non-null logos and process each team.
     */
    public function getTeamsDetails()
    {
        $countries = [
            'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Argentina', 'Australia', 'Austria', 'Azerbaijan',
            'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bhutan', 'Bolivia',
            'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cabo Verde',
            'Cambodia', 'Cameroon', 'Canada', 'Central African Republic', 'Chad', 'Chile', 'China', 'Colombia', 'Comoros',
            'Costa Rica', 'Croatia', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic',
            'Ecuador', 'Great Britain', 'Egypt', 'England', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Eswatini', 'Ethiopia',
            'Fiji', 'Finland', 'France', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Greece', 'Grenada', 'Guatemala',
            'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq',
            'Ireland', 'Israel', 'Italy', 'Ivory Coast', 'Macau of China', 'Guam Island', 'Chinese Taipei', 'Jamaica', 'Japan',
            'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia',
            'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta',
            'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico', 'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro',
            'Congo', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua',
            'Niger', 'Nigeria', 'North Korea', 'North Macedonia', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Panama','Palestine','Trinidad Tobago','Korea Rep','Timor Leste', 'Papua New Guinea',
            'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis',
            'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal',
            'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa',
            'South Korea', 'South Sudan', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Sweden', 'Switzerland', 'Syria', 'Taiwan',
            'Tajikistan', 'Tanzania', 'Thailand', 'Hong Kong', 'Timor-Leste', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia',
            'Turkey', 'Turkmenistan', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States of America',
            'Uruguay','Brasil', 'Puerto Rico', 'Korea','Armenia', 'UAE','Uzbekistan', 'Vanuatu', 'Vatican City', 'Venezuela', 'Vietnam', 'Yemen', 'Zambia','USA','Netherland', 'Zimbabwe', 'A1C2', 'Holland', 'Srbija C.Gora', 'Tahiti', 'Selection Mediterranean'
        ];

        // Sorgunu qurmaq
        $teams = Team::whereNotNull('team_name') // team_name NULL ola bilməz
        ->where('team_name', '!=', 'WIN') // team_name "WIN" ola bilməz
        ->where('team_name', 'NOT LIKE', '%Play-off%') // team_name "Play-off" içərməməlidir
        ->where('team_name', 'NOT LIKE', '%WIN')
            ->where('last_detail_update_time',null)
            ->where('team_name', 'NOT LIKE', '%loser%')
            ->where('team_name', 'NOT LIKE', '%winner%')
            ->whereRaw('LENGTH(team_name) >= 5')
            ->where(function($query) use ($countries) {
                foreach ($countries as $country) {
                    $query->whereRaw("team_name NOT LIKE ?", ["%$country%"]);
                }
            })
            ->get();

        if ($teams->isEmpty()) {
            $this->warn('No teams found with a non-null logo.');
            return;
        }

        // Initialize the progress bar
        $bar = $this->output->createProgressBar($teams->count());
        $bar->start();

        foreach ($teams as $team) {
            $this->getTeamsDetailsMethod($team->team_id);
            $team = Team::where('team_id', $team->team_id)->first();
            $team->update(['last_detail_update_time'=> now()] );
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(); // Move to the next line after the progress bar
    }

    /**
     * Fetch and update details for a single team.
     *
     * @param int $id
     */
    public function getTeamsDetailsMethod($id)
    {
        try {
            $url = "https://football.nowgoal19.com/jsdata/teamInfo/teamdetail/tdl{$id}_en.js";

            // Initialize cURL
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects if any
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Enable SSL verification for security

            // Execute the cURL request
            $response = curl_exec($ch);

            // Check for cURL errors
            if ($response === false) {
                $error = curl_error($ch);
                curl_close($ch);
                $this->error("cURL error while fetching team ID {$id}: {$error}");
                return;
            }

            // Close the cURL session
            curl_close($ch);
            $league_id = null;
            if (preg_match('/var\s+teamCount \s*=\s*(\[[^\]]*\])/i', $response, $counts)) {
                $league_id = explode(',', $counts[1])[4];
            }

            // Use regex to extract the teamDetail array
            if (preg_match('/var\s+teamDetail\s*=\s*(\[[^\]]*\])/i', $response, $matches)) {
                $teamDetailJsArray = $matches[1];
                // Decode the JavaScript array into a PHP array
                $teamDetail = explode("',", $teamDetailJsArray);

                // Process each field with checks
                $address = isset($teamDetail[14]) ? trim(str_replace(['"', "'"], '', $teamDetail[14])) : null;
                $logo = isset($teamDetail[3]) ? trim(str_replace(['"', "'"], '', $teamDetail[3])) : null;
                $city = isset($teamDetail[6]) ? trim(str_replace(['"', "'"], '', $teamDetail[6])) : null;
                $stadium = isset($teamDetail[9]) ? trim(str_replace(['"', "'"], '', $teamDetail[9])) : null;
                $date = isset($teamDetail[11]) ? trim(str_replace(['"', "'"], '', $teamDetail[11])) : null;
                $info = isset($teamDetail[13]) ? trim(str_replace(['"', "'"], '', $teamDetail[13])) : null;
                $website = isset($teamDetail[12]) ? trim(str_replace(['"', "'"], '', $teamDetail[12])) : null;
                $stadium_capacity = isset($teamDetail[10]) ? trim(str_replace(['"', "'"], '', $teamDetail[10])) : null;
                $team_name = isset($teamDetail[2]) ? trim(str_replace(['"', "'"], '', $teamDetail[2])) : null;

                // Find the team in the database
                $findedTeam = Team::where('team_id', $id)->first();

                if ($findedTeam) {

                    // Update the team details
                    $findedTeam->update([
                        'team_name' => $team_name,
                        'city' => $city,
                        'stadium' => $stadium,
                        'league_id' => $league_id,
                        'date' => $date,
                        'info' => $info,
                        'address' => $address,
                        'website' => $website,
                        'stadium_capacity' => $stadium_capacity,
                        'logo' => str_replace(']]', '', $logo),
                    ]);

                    // Show team name and ID in the terminal
                    $this->info("Team ID {$id} ({$team_name}) details updated successfully.");
                } else {
                    $this->warn("Team ID {$id} not found in the database.");
                }
            } else {
                $this->warn("teamDetail array not found for Team ID {$id}.");
            }
        } catch (\Exception $e) {
            $this->error("An error occurred while processing Team ID {$id}: " . $e->getMessage());
        }
    }

}
