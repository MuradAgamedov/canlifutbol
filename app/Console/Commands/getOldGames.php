<?php

namespace App\Console\Commands;

use App\Models\Cup;
use App\Models\Game;
use App\Models\League;
use App\Models\Season;
use App\Models\Team;
use App\Models\Tgame;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class getOldGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:getOldGames';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store old games';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentYear = now()->year;


        // Sezonları filtr edin: Cari il və ya cari ili əhatə edən başlıqlar
        $seasons = Season::where(function ($query) use ($currentYear) {
            $query->where('title', $currentYear) // Mövsüm cari ilə bərabərdirsə
            ->orWhere('title', 'LIKE', '%-' . $currentYear); // Mövsüm iki il arasını əhatə edirsə
        })
            ->get();

        $seasonCount = $seasons->count();
        $this->info("Fetching games for {$seasonCount} seasons...");

        if ($seasonCount === 0) {
            $this->info("No seasons to process.");
            return;
        }

        // Create a progress bar
        $progressBar = $this->output->createProgressBar($seasonCount);
        $progressBar->start();

        foreach ($seasons as $season) {
            $this->line("Processing season: {$season->id} (League ID: {$season->league_id})");
            try {
                $this->getGames($season->title, $season->league_sub_id, $season->id);

                // Mark the season as processed
                $this->info("Season {$season->title} processed successfully.");
            } catch (\Exception $e) {
                // Log the error and continue to the next season
                $this->error("Error processing season {$season->title}: " . $e->getMessage());
                Log::error("Error processing season {$season->title}: " . $e->getMessage());
            }

            // Advance the progress bar
            $progressBar->advance();
        }

        // Finish the progress bar
        $progressBar->finish();
        $this->newLine();
        $this->info("Finished processing all seasons.");
        Tgame::whereNull("home_club_goals")
            ->orWhereNull("away_club_goals")
            ->delete();
    }

    public function getGames($season_title, $league_id, $season_id)
    {
        $client = new Client();
        $url = "https://football.nowgoal29.com/jsData/matchResult/$season_title/$league_id.js";
        $this->info($url);

        try {
            $response = $client->get($url);
            $body = $response->getBody()->getContents();

            preg_match_all('/jh\["R_\d+"\]\s*=\s*(\[[\s\S]*?\]);/', $body, $matches);

            $rounds = [];
            if (!empty($matches[1])) {
                foreach ($matches[1] as $roundData) {
                    $cleanData = preg_replace([
                        '/,\s*([\]}])/', // Remove trailing commas
                        "/'/",           // Replace single quotes with double quotes
                        '/(?<!")(\b[a-zA-Z]+\b)(?!")/', // Add quotes to unquoted words
                    ], [
                        '$1',
                        '"',
                        '"$1"',
                    ], $roundData);

                    if ($cleanData !== null) {
                        $rounds[] = explode('],', str_replace('[', '', $cleanData));
                    }
                }
            } else {
                throw new \Exception("No round data found for $season_title / $league_id");
            }

            $roundNumber = 1;

            foreach ($rounds as $roundGames) {
                foreach ($roundGames as $game) {
                    $oneGame = explode(',', $game);

                    $game_id = str_replace('"', '', $oneGame[0] ?? null);

                    $gameData = [
                        'league_name' => optional(
                                League::where('league_id', str_replace('"', '', $oneGame[1] ?? null))->first()
                            )->league_name ?? null,
                        'league_id' => str_replace('"', '', $oneGame[1] ?? null),
                        'status' => str_replace('"', '', $oneGame[2] ?? null),
                        'start_time' => isset($oneGame[3]) ? str_replace('"', '', $oneGame[3]) : null,
                        'home_club_id' => str_replace('"', '', $oneGame[4] ?? null),
                        'away_club_id' => str_replace('"', '', $oneGame[5] ?? null),
                        "home_club_name" => optional(
                            Team::where('team_id', str_replace('"', '', $oneGame[4] ?? null))->first()
                        )->team_name,
                        "away_club_name" => optional(
                            Team::where('team_id', str_replace('"', '', $oneGame[5] ?? null))->first()
                        )->team_name,
                        'home_club_goals' => isset($oneGame[6]) ? str_replace('"', '', explode('-', $oneGame[6])[0] ?? null) : null,
                        'away_club_goals' => isset($oneGame[6]) ? str_replace('"', '', explode('-', $oneGame[6])[1] ?? null) : null,
                        'home_club_half_score' => isset($oneGame[7]) ? str_replace('"', '', explode('-', $oneGame[7])[0] ?? null) : null,
                        'away_club_half_score' => isset($oneGame[7]) ? str_replace('"', '', explode('-', $oneGame[7])[1] ?? null) : null,
                        "season_id" => $season_id,
                        "round" => $roundNumber
                    ];

                    // **Əgər `league_id = 36`-dırsa, terminalda `game_id` çap et**
                    if ($gameData['league_id'] == 36) {
                        $this->info("League 36: Game ID - " . $game_id);
                    }

                    // **Əgər oyun varsa yenilə, yoxdursa yarat**
                    Tgame::updateOrCreate(
                        ['game_id' => $game_id],  // Açar (key) -> Əgər bu `game_id` varsa `update` olunacaq, yoxdursa `create` ediləcək
                        $gameData
                    );
                }

                $roundNumber++;
            }
        } catch (\Exception $e) {
            throw new \Exception("Failed to fetch or process games: " . $e->getMessage());
        }
    }


}
