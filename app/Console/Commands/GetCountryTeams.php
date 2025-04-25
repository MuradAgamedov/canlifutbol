<?php

namespace App\Console\Commands;

use App\Models\CountryTeam;
use App\Models\League;
use App\Models\Team;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GetCountryTeams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:getCountryTeams';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->getTeams();
    }

    /**
     * Fetch and process leagues and teams.
     */
    public function getTeams()
    {
        $ids = [
            650,
            892,
            44,
            185,
            76,
            261,
            609,
            387,
            730,
            1252,
            222,
            434,
            1861,
            2042,
            2043,
            768,
            349,
            329,
            300,
            845,
            1180,
            1181,
            2595,
            1294,
            1322,
            1489,
            1726,
            1223,
            2497,
            648,
            651,
            653,
            88,
            649,
            1218,
            2348,
            1315,
            1310,
            1307,
            1280,
            1679,
            1244,
            1229,
            1219,
            2401,
            1361,
            1864,
            223,
            114,
            1164,
            215,
            210,
            256,
            526,
            668,
            561,
            690,
            605,
            741,
            608,
            836,
            875,
            1306,
            1230,
            2503,
            2576,
            2499,
            224,
            232,
            424,
            489,
            487,
            429,
            621,
            679,
            682,
            691,
            1121,
            1123,
            1171,
            1177,
            1212,
            1289,
            1282,
            1964,
            2059,
            2089,
            2482,
            1345,
            1368,
            251, 53, 291, 293, 1124, 470, 655, 161, 405,
            560, 571, 629, 725, 731, 564, 1331, 798, 295, 435,
            689, 516, 1253, 699, 399, 726, 1444, 1625, 1638,
            1403, 1404, 1370, 503, 1254, 1220, 1385, 1408,
            2319, 2385, 2402, 2409, 2471,2594,
            1128,
            1149,
            1332,
            1953,
            1965,
            2534,
            2545,
            93,
            176,
            581,
            624,
            781,
            931,
            1052,
            1052,
            1311,
            1297,
            1722,
            1812,
            1226,
            1213,
            2607,
            2615
        ];




        $leagues = League::whereIn('league_id', $ids)->get();

        // Progress bar for leagues
        $this->info('Fetching teams from leagues...');
        $leagueBar = $this->output->createProgressBar($leagues->count());
        $leagueBar->start();

        foreach ($leagues as $league) {
            $this->getTeamsData($league->league_id);
            $league = League::where('league_id', $league->league_id)->first();
            $league->update(['last_collect_time' => now()]);
            $leagueBar->advance(); // Advance the progress bar
        }

        $leagueBar->finish(); // Finish the progress bar
        $this->newLine(); // Add a newline after the progress bar
        $this->info('Team fetching completed successfully.');
    }

    /**
     * Fetch teams data for a league.
     *
     * @param int $leagueId
     */
    public function getTeamsData($leagueId)
    {
        try {
            $url = "https://football.nowgoal19.com/jsdata/teaminfo/team{$leagueId}.js?v=20240925052403";

            $client = new Client();
            $response = $client->request('GET', $url);
            $body = $response->getBody()->getContents();

            if (preg_match("/var\s+arrTeam\s*=\s*(\[\[.*?\]\]);/i", $body, $matches)) {
                $arrTeamString = $matches[1];
                $arrTeam = explode('],', $arrTeamString);

                $this->info("Fetching teams for league ID {$leagueId}...");
                $teamBar = $this->output->createProgressBar(count($arrTeam));
                $teamBar->start();

                foreach ($arrTeam as $team) {
                    $t = explode(',', $team);

                    // Şərti yoxlama
                    if (
                        !preg_match("/A1|B1|C1|G1|D1|H1|F1|Winner|Semifinal|WIN|OLSUN/i", $t[3]) &&
                        !preg_match("/\[\w{2}\]/", $t[3])
                    ) {
                        $teamId = str_replace('[', '', $t[0]);
                        $teamName = str_replace("'", '', $t[3]);
                        $slug = Str::slug($teamName);
                        $logo = str_replace(']]', '', str_replace("'", '', $t[5]));

                        // Logo üçün şərt
                        if (!$logo || strlen($logo) < 3) {
                            $logo = null;
                        }

                        // CountryTeam modelində yoxlama və əlavə
                        $countryTeamData = [
                            'team_id' => $teamId,
                            'league_id' => $leagueId,
                            'slug' => $slug,
                            'team_name' => $teamName,
                            'logo' => $logo,
                            'team_type' => 2,
                        ];

                        $findedCountryTeam = CountryTeam::where('team_id', $teamId)->first();
                        if ($findedCountryTeam) {
                            $findedCountryTeam->update($countryTeamData);
                        } else {
                            CountryTeam::create($countryTeamData);
                        }

                        // Team modelində yoxlama və əlavə
                        $teamData = [
                            'team_id' => $teamId,
                            'league_id' => $leagueId,
                            'slug' => $slug,
                            'team_name' => $teamName,
                            'logo' => $logo,
                            'team_type' => 1,
                        ];

                        $findedTeam = Team::where('team_id', $teamId)->first();
                        if ($findedTeam) {
                            $findedTeam->update($teamData);
                        } else {
                            Team::create($teamData);
                        }
                    }

                    $teamBar->advance();
                }

                $teamBar->finish();
                $this->newLine();
            }
        } catch (\Exception $e) {
            $this->error("Error fetching teams for league ID {$leagueId}: " . $e->getMessage());
        }
    }

}
