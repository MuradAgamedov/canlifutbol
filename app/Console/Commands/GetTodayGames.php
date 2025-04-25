<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\Event;
use App\Models\Game;
use App\Models\League;
use App\Models\Player;
use App\Models\Season;
use App\Models\Team;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GetTodayGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:getTodayGames';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and update today\'s games';

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

    public function getPredictions()
    {
        $url = 'https://live15.nowgoal29.com/gf/data/odds/en/goal8.xml';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        // Check if there's any error with the request
        if ($response === false) {
            $this->sendSlackNotification('Get-predictions: Error fetching the data.');
            die('Error fetching the data.');
        }

        // Check for the HTTP status code
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Handle 404 and other non-200 HTTP codes
        if ($httpCode === 404) {
            $this->sendSlackNotification('Get-predictions: Error: 404 Not Found.');
            die('Error fetching the data: 404 Not Found.');
        } elseif ($httpCode >= 300 && $httpCode < 400 && !empty($locationHeader)) {
            $this->sendSlackNotification(
                'Error: Get-prediction: Document Moved. Check the new location: ' . $locationHeader
            );
            die('Error fetching the data: Document has moved to a new location. Check the location header: ' . $locationHeader);
        } elseif ($httpCode !== 200) {
            $this->sendSlackNotification('Error: Get-prediction: HTTP ' . $httpCode);
            die('Error fetching the data: HTTP status ' . $httpCode);
        }


        $pattern = '/(\d+),(\d+),(-?\d+\.?\d*),(\d+\.\d+),(\d+\.\d+),(\d+),(\d+\.\d+),(\d+\.\d+),(\d+\.\d+),(\d+),(\d+\.\d+),(\d+\.\d+),(\d+)/';

        preg_match_all($pattern, $response, $matches, PREG_SET_ORDER);

        $games = [];


        foreach ($matches as $match) {
            $gameFound = Game::where('game_id', (int)$match[1])->first();
            if ($gameFound) {
                $gameFound->update(
                    [
                        'bet1' => $match[7],
                        'betx' => $match[8],
                        'bet2' => $match[9],
                    ]
                );
            }
        }
    }

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
            // Oyunları alırıq
            $games = $this->getActiveGames();
            $this->getPredictions();
            // Komandaları yoxla və əlavə et
            $this->addTeamsFromGames($games);
        } catch (\Exception $e) {
            die('Xəta baş verdi: ' . $e->getMessage());
            $this->sendSlackNotification(
                'Xəta baş verdi-getLiveGames: ' . $e->getMessage() . ' | Fayl: ' . $e->getFile(
                ) . ' | Sətir: ' . $e->getLine()
            );
        }
        return 0;
    }

    public function addTeamsFromGames($games)
    {
        foreach ($games as $game) {
            // Home team yoxlama və əlavə etmə
            $homeTeam = Team::where('team_id', $game['home_club_id'])->first();
            if (!$homeTeam) {
                Team::create([
                    'team_id' => $game['home_club_id'],
                    'team_name' => $game['home_club_name'],
                    'slug' => Str::slug($game['home_club_name']),
                    'logo' => 'images/club.png',  // Varsayılan logo
                    'league_id' => $game['league_id'],
                    'team_type' => null  // team_type boş olaraq əlavə edilir
                ]);
            }

            // Away team yoxlama və əlavə etmə
            $awayTeam = Team::where('team_id', $game['away_club_id'])->first();
            if (!$awayTeam) {
                Team::create([
                    'team_id' => $game['away_club_id'],
                    'team_name' => $game['away_club_name'],
                    'slug' => Str::slug($game['away_club_name']),
                    'logo' => 'images/club.png',  // Varsayılan logo
                    'league_id' => $game['league_id'],
                    'team_type' => null  // team_type boş olaraq əlavə edilir
                ]);
            }
        }
    }


    public function getActiveGames()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get('https://live14.nowgoal29.com/gf/data/bf_en-idn.js?1725473673000');
        $content = (string)$response->getBody();

        preg_match_all('/C\[(\d+)\]=\[(.*?)\];/', $content, $findedCountries);

        $countries = [];

        foreach ($findedCountries[2] as $country) {
            $slug = Str::slug(str_replace("'", "", explode(',', $country)[1]));
            $findedCountry = Country::where('slug', $slug)->first();

            if (!$findedCountry) {
                Country::create([
                    'country_id' => Country::orderByDesc('id')->latest()->first()->country_id + 1,
                    'name' => str_replace("'", "", explode(',', $country)[1]),
                    'slug' => $slug
                ]);
            }
            $countries[str_replace("'", "", explode(',', $country)[0])] = [
                'country_id' => Country::where('slug', $slug)->first() ? Country::where('slug', $slug)->first(
                )->country_id : null,
                'name' => str_replace("'", "", explode(',', $country)[1]),
                'f_page_id' => str_replace("'", "", explode(',', $country)[0])
            ];
        }

        // Step 1: Parse B[] array to get leagues
        preg_match_all('/B\[(\d+)\]=\[(.*?)\];/', $content, $matchesB);

        $leagues = [];
        foreach ($matchesB[1] as $index => $leagueKey) {
            $leagueData = $this->splitData($matchesB[2][$index]);
            $c_id = isset($leagueData[9]) ? trim($leagueData[9], "'") : trim($leagueData[8], "'");
            $findedLeague = League::where('league_id', trim($leagueData[0], "'"))->first();
            if (!$findedLeague) {
                League::create([
                    'league_id' => trim($leagueData[0], "'"),
                    'league_short_name' => trim($leagueData[1], "'"),
                    'league_name' => trim($leagueData[2], "'"),
                    'slug' => Str::slug(trim($leagueData[2], "'")),
                    'pos' => 10000,
                    'country_id' => isset($countries[$c_id]) ? $countries[$c_id]['country_id'] : null
                ]);
            }
            $leagues[$leagueKey] = [
                'league_id' => trim($leagueData[0], "'"),
                'league_short_name' => trim($leagueData[1], "'"),
                'league_name' => trim($leagueData[2], "'"),
                'slug' => Str::slug(trim($leagueData[2], "'")),
                'country_id' => isset($countries[$c_id]) ? $countries[$c_id]['country_id'] : null
            ];
        }

        // Parse games array
        preg_match_all('/A\[([0-9]+)\]=\[(.*?)\];/', $content, $matches);
        $games = [];
        foreach ($matches[2] as $game) {
            $gameData = explode(',', $game);
            $leagueReference = trim($gameData[1], "'");

            // Get actual league_id from leagues mapping
            $league_id = isset($leagues[$leagueReference]) ? $leagues[$leagueReference]['league_id'] : null;
            $league_name = isset($leagues[$leagueReference]) ? $leagues[$leagueReference]['league_name'] : null;

            $games[] = [
                'game_id' => $gameData[0],
                'league_id' => $league_id,
                'league_name' => $league_name,
                'home_club_id' => trim($gameData[2], "'"),
                'away_club_id' => trim($gameData[3], "'"),
                'home_club_name' => trim($gameData[4], "'"),
                'away_club_name' => trim($gameData[5], "'"),
                'start_time' => \Carbon\Carbon::parse(trim($gameData[6], "'"))->timestamp,
                'last_update_time' => \Carbon\Carbon::parse(trim($gameData[7], "'"))->timestamp,
                'home_club_goals' => $gameData[9],
                'away_club_goals' => $gameData[10],
                'home_club_half_score' => $gameData[11],
                'away_club_half_score' => $gameData[12],
                'home_club_red_cards_count' => $gameData[13],
                'away_club_red_cards_count' => $gameData[14],
                'home_club_yellow_cards_count' => $gameData[15],
                'away_club_yellow_cards_count' => $gameData[16],
                'season_id' => Season::where('league_id', 36)->first()->id,
                'status' => $gameData[8],
            ];
        }

        // Yeni Funksionallıq: Status 0 olmayan uyğun olmayan oyunların statusunu yenilə
        $this->updateNonMatchingGames($games);

        // Add data to the database
        $this->addToDatabase($games);

        // Add events
        $this->addGameEvents($games);

        // Return games array so it can be used elsewhere
        return $games;
    }


    /**
     * Update non-matching games.
     *
     * @param array $games
     * @return void
     */
    public function updateNonMatchingGames($games)
    {
        // Çəkilən oyunların `game_id`-lərini array şəklində alırıq
        $gameIds = array_column($games, 'game_id');

        // Statusu 0 olmayan və uyğun olmayan oyunları seçirik
        $nonMatchingGames = Game::whereNotIn('game_id', $gameIds)
            ->where('status', '!=', 0)
            ->get();

        // Bu oyunların statusunu 4 edirik
        foreach ($nonMatchingGames as $nonMatchingGame) {
            $nonMatchingGame->update(['status' => 4]);
        }
    }

    /**
     * Add data to the database.
     *
     * @param array $games
     * @return void
     */
    public function addToDatabase($games)
    {
        foreach ($games as $game) {
            // Check if the game already exists based on start_time, home_club_id, and away_club_id
            $existingGame = Game::where('game_id', $game['game_id'])
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

    /**
     * Add game events to the database.
     *
     * @param array $games
     * @return void
     */
    public function addGameEvents($games)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get('https://live14.nowgoal29.com/gf/data/detailIn.js');
        $content = (string)$response->getBody();

        // Extract events from d_f object
        preg_match_all('/d_f\[(\d+)\]=\[(.*?)\];/', $content, $matches);
        foreach ($matches[1] as $index => $gameId) {
            $eventData = $matches[2][$index];
            $eventsForGame = [];
            preg_match_all('/\[(.*?)\]/', $eventData, $eventMatches);

            foreach ($eventMatches[1] as $event) {
                $eventDetails = explode(',', $event);
                $foundPlayer = Player::where('playerId', $eventDetails[3])->first();

                // Update or create the main player
                if ($foundPlayer) {
                    $foundPlayer->update([
                        'teamId' => $eventDetails[2],
                        'playerId' => $eventDetails[3],
                        'name' => trim($eventDetails[6], "'"),
                    ]);
                } else {
                    Player::create([
                        'teamId' => $eventDetails[2],
                        'playerId' => $eventDetails[3],
                        'name' => trim($eventDetails[6], "'"),
                    ]);
                }

                // Assist player kontrolu - əgər assist player mövcuddursa, yalnız o zaman əlavə edilir
                if (!empty($eventDetails[7]) && $eventDetails[4] !== '') {
                    $foundAssistPlayer = Player::where('playerId', $eventDetails[4])->first();

                    if ($foundAssistPlayer) {
                        $foundAssistPlayer->update([
                            'teamId' => $eventDetails[2],
                            'playerId' => $eventDetails[4],
                            'name' => trim($eventDetails[7], "'"),
                        ]);
                    } else {
                        Player::create([
                            'teamId' => $eventDetails[2],
                            'playerId' => $eventDetails[4],
                            'name' => trim($eventDetails[7], "'"),
                        ]);
                    }
                }

                $eventsForGame[] = [
                    "game_id" => $gameId,
                    'event_type' => $eventDetails[0],
                    'minute' => $eventDetails[1],
                    'team_id' => $eventDetails[2],
                    'player_id' => $eventDetails[3],
                    'player_name' => trim($eventDetails[6], "'"),
                    'assist_palyer_id' => !empty($eventDetails[4]) ? $eventDetails[4] : null,
                    'assist_player' => !empty($eventDetails[7]) ? trim($eventDetails[7], "'") : null,
                ];
            }

            // Yeni Yanaşma: Mövcud eventləri sil və yeni eventləri yaz
            foreach ($games as $game) {
                if ($game['game_id'] == $gameId) {
                    // Əvvəlcə, həmin oyun üçün olan bütün eventləri sil
                    Event::where('game_id', $gameId)->delete();

                    // Yeni eventləri əlavə et
                    foreach ($eventsForGame as $event) {
                        Event::create($event);
                    }
                }
            }
        }
    }


    private function splitData($dataStr)
    {
        $pattern = "/'[^']*'|[^,]+/";
        preg_match_all($pattern, $dataStr, $matches);
        $fields = $matches[0];

        // Trim quotes and spaces
        $fields = array_map(function ($field) {
            return trim($field, " '\"");
        }, $fields);

        return $fields;
    }
}
