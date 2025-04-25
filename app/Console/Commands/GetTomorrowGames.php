<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\Game;
use App\Models\League;
use App\Models\Team;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GetTomorrowGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:getTomorrowGames';

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
    public function sendSlackNotification($message)
    {
        $webhook_url = env('slack_url'); // Webhook URL-i

        $payload = json_encode([
            "text" => $message
        ]);

        $ch = curl_init($webhook_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Uğurla göndərilib-göndərilmədiyini yoxlayın
        if ($httpcode == 200) {
            $this->info("Bildiriş uğurla göndərildi!");
        } else {
            $this->error("Bildirişi göndərmək mümkün olmadı! HTTP kodu: " . $httpcode);
        }
    }
    public function handle()
    {
        try {
            $this->getTomorowGames();
            echo "tomorrow games: success";
        } catch (\Exception $e) {
            die('GetTomorrowGames - xəta baş verdi: ' . $e->getMessage());
            $this->sendSlackNotification(
                'Xəta baş verdi-getTomorrowGames: ' . $e->getMessage() .
                    ' | Fayl: ' . $e->getFile() .
                    ' | Sətir: ' . $e->getLine()
            );

        }

        return 0;
    }

    private function splitData($dataStr)
    {
        $pattern = "/'[^']*'|[^,]+/";
        preg_match_all($pattern, $dataStr, $matches);
        $fields = $matches[0];

        // Trim quotes and spaces
        $fields = array_map(function($field) {
            return trim($field, " '\"");
        }, $fields);

        return $fields;
    }
    public function getTomorowGames()
    {
        // Dynamic date for tomorrow
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
        $url = "https://live.nowgoal17.com/ajax/SoccerAjax?type=6&date={$tomorrow}&order=league&timezone=UTC";

        // Guzzle HTTP client to send request
        $client = new Client();
        $response = $client->get($url);

        // Read the data
        $data = $response->getBody()->getContents();

        preg_match_all('/B\[(\d+)\]=\[(.*?)\];/', $data, $matchesB);

        $leagues = [];
        foreach ($matchesB[1] as $index => $leagueKey) {
            // Split by comma, considering quoted strings
            $leagueData = $this->splitData($matchesB[2][$index]);
            // Assign league details
            $leagues[$leagueKey] = [
                'league_id' => trim($leagueData[0], "'"),
                'league_name' => trim($leagueData[1], "'"),
                'league_full_name' => trim($leagueData[2], "'"),
                'color' => trim($leagueData[3], "'"),
                'flag' => trim($leagueData[4], "'"),
                'url' => trim($leagueData[5], "'"),
                // Add more fields if necessary
            ];
        }

        // Parse games array
        preg_match_all('/A\[([0-9]+)\]=\[(.*?)\];/', $data, $matches);


        $games = [];
        foreach ($matches[2] as $game) {
            $gameData = explode(',', $game);
            $leagueReference = trim($gameData[1], "'");

            // Get actual league_id from leagues mapping
            $league_id = isset($leagues[$leagueReference]) ? $leagues[$leagueReference]['league_id'] : null;
            $league_name = isset($leagues[$leagueReference]) ? $leagues[$leagueReference]['league_full_name'] : null;
            $start_date = $gameData[6].'-'.((int)$gameData[7]+1).'-'.$gameData[8]. ' '.$gameData[9].':'.$gameData[10]. ':'.$gameData[11];
            $games[] = [
                'game_id' => $gameData[0],
                'league_id' => $league_id,
                'league_name' => $league_name,
                'home_club_id' => trim($gameData[2], "'"),
                'away_club_id' => trim($gameData[3], "'"),
                'home_club_name' => trim($gameData[4], "'"),
                'away_club_name' => trim($gameData[5], "'"),
                'start_time' => \Carbon\Carbon::parse(trim($start_date, "'"))->timestamp,
                'status' => 0,
            ];
            $findedHomeTeam = Team::where('team_id', trim($gameData[2]))->first();
            if($findedHomeTeam) {
                $findedHomeTeam->update([
                    'league_id' => $league_id,
                    "team_id" => trim($gameData[2], "'"),
                    "team_name" => trim($gameData[4], "'"),
                    'slug' => Str::slug(trim($gameData[4], "'"),)
                ]);
            } else {
                Team::create([
                    'league_id' => $league_id,
                    "team_id" => trim($gameData[2], "'"),
                    "team_name" => trim($gameData[4], "'"),
                    'slug' => Str::slug(trim($gameData[4], "'"),)
                ]);
            }

            $findedAwayTeam = Team::where('team_id', trim($gameData[3]))->first();
            if($findedAwayTeam) {
                $findedAwayTeam->update([
                    'league_id' => $league_id,
                    "team_id" => trim($gameData[3], "'"),
                    "team_name" => trim($gameData[5], "'"),
                    'slug' => Str::slug(trim($gameData[5], "'"),)
                ]);
            } else {
                Team::create([
                    'league_id' => $league_id,
                    "team_id" => trim($gameData[3], "'"),
                    "team_name" => trim($gameData[5], "'"),
                    'slug' => Str::slug(trim($gameData[5], "'"),)
                ]);
            }
        }
        $this->addToDatabase($games);

    }
    public function addToDatabase($games)
    {
        foreach ($games as $game) {
            // Check if the game already exists based on start_time, home_club_id, and away_club_id
            $existingGame = Game::where('home_club_id', $game['home_club_id'])

                ->first();

            if ($existingGame) {
                // Update existing game
                $existingGame->update($game);
            } else {
                // Create new game
                Game::create($game);
            }
        }
    }
}
