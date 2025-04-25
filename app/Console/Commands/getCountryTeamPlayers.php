<?php

namespace App\Console\Commands;

use App\Models\CountryTeam;
use App\Models\Player;
use App\Models\Team;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class getCountryTeamPlayers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:getCountryTeamsPlayers';

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
        $this->getPlayers();
    }

    public function getPlayers()
    {
        $teams = CountryTeam::all();


        // Start the progress bar
        $this->output->progressStart(count($teams));

        foreach ($teams as $team) {
            $team = CountryTeam::where('team_id', $team->team_id)->first();
            try {
                $this->getPlayersData($team->team_id, $team->team_type);
                $team->update(['last_collect_time' => now()]);
                // Success message for each team
                $this->info("Success: Added players for team {$team->name} (team_id: {$team->team_id})");
            } catch (\Exception $e) {
                $this->error("Error collecting data for team ID {$team->team_id}: " . $e->getMessage());
            }

            // Advance the progress bar
            $this->output->progressAdvance();
        }

        // Complete the progress bar
        $this->output->progressFinish();
    }

    public function getPlayersData($id, $type)
    {

        try {
            // Modify the URL based on your requirements
            $url = "https://football.nowgoal19.com/jsdata/teamInfo/teamdetail/tdl{$id}_en.js";

            // Initialize Guzzle client
            $client = new Client();

            // Fetch the content from the URL
            $response = $client->get($url);
            $body = $response->getBody()->getContents();

            // Extract JSON data
            preg_match('/var\s+lineupDetail\s*=\s*(\[[\s\S]*?\]);/', $body, $matches);
            $jsonString = preg_replace("/'/", '"', $matches[1]);
            $jsonString = preg_replace('/,(\s*[\]}])/', '\1', $jsonString);
            $lineUp = preg_split('/(?<=\])\s*,\s*(?=\[)/', $jsonString);

            $lineUp = array_map(function ($item) {
                return explode(',', trim($item, "[]"));
            }, $lineUp);

            // Əvvəlcə bu komandanın mövcud oyunçularının country_team_id sahəsini null edirik
            Player::where('teamId', $id)->update(['country_team_id' => null]);
            $this->info("Started team id : {$id}");
            foreach ($lineUp as $data) {
                // Clean data
                $playerId = isset($data[0]) ? str_replace('"', '', $data[0]) : null;
                $number = isset($data[1]) ? str_replace('"', '', $data[1]) : null;
                $name = isset($data[2]) ? str_replace('"', '', $data[2]) : null;
                $birthdayRaw = isset($data[5]) ? str_replace('"', '', $data[5]) : null;
                $height = isset($data[6]) ? str_replace('"', '', $data[6]) : null;
                $weight = isset($data[7]) ? str_replace('"', '', $data[7]) : null;
                $position = isset($data[8]) ? str_replace('"', '', $data[8]) : null;
                $country = isset($data[9]) ? str_replace('"', '', $data[9]) : null;
                $value = isset($data[11]) ? str_replace('"', '', $data[11]) : null;

                $birthday = $birthdayRaw ? date('Y-m-d', strtotime($birthdayRaw)) : null;

                // Check if player exists
                $player = Player::where('playerId', $playerId)->first();
                $photoUrl = null;

                if ($player) {
                    // Əgər oyunçu mövcuddursa, onun məlumatlarını yeniləyirik və country_team_id sahəsini bu komandanın id-si ilə doldururuq
                    $player->update([

                        'country_team_id' => $id, // Country team ID-ni yeniləyirik
                        'country_team_number' => $number
                    ]);

                    $this->info("Updated Player ID: {$playerId}");
                } else {
                    // Əgər oyunçu mövcud deyilsə, yeni bir oyunçu əlavə edirik və country_team_id sahəsini təyin edirik
                    Player::create([
                        'playerId' => $playerId,
                        'number' => $number,
                        'position' => $position,
                        'country' => $country,
                        'value' => $value,
                        'height' => $height,
                        'weight' => $weight,
                        'teamId' => $id,
                        'name' => $name,
                        'slug' => Str::slug($name),
                        'birthday' => $birthday,
                        'photo' => $photoUrl,
                        'team_type' => $type,
                        'country_team_id' => $id // Yeni oyunçu üçün Country team ID təyin edilir
                    ]);

                    $this->info("Created new Player ID: {$playerId}");
                }
            }
        } catch (\Exception $e) {
            $this->error("Error fetching player data for team ID {$id}: " . $e->getMessage());
        }
    }

}
