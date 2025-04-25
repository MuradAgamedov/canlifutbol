<?php

namespace App\Console\Commands;

use App\Models\PlayerTech;
use App\Models\Season;
use App\Traits\LeaguesAndGamesTrait;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class GetPlayerTech extends Command
{
    use LeaguesAndGamesTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:getPlayerTech';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and process player technical data for all seasons';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $leagues = $this->getSelectedTypeLeagues('active');
        $totalLeagues = $leagues->count();

        $this->info("Processing {$totalLeagues} leagues...\n");

        // Create a progress bar for leagues
        $leagueBar = $this->output->createProgressBar($totalLeagues);
        $leagueBar->start();

        foreach ($leagues as $index => $league) {
            $currentLeague = $index + 1;
            $this->info("\nProcessing league {$currentLeague}/{$totalLeagues}: League ID - {$league->league_id}");

            try {
                $season = $league->lastSeason;
                if (!$season) {
                    $this->warn("No seasons found for league {$league->league_id}. Skipping...");
                    $leagueBar->advance();
                    continue;
                }

                $this->info("Found season: {$season->title} (Season ID: {$season->id}).");

                $this->getTech($season->title, $season->id, $league->league_id);

                $this->info("Processed season {$season->title} for league {$league->league_id}.");
            } catch (\Exception $e) {
                $url = "https://football.nowgoal29.com/jsdata/count/{$league->lastSeason->title}/playertech_{$league->league_id}.js";
                \Log::error("Error processing league {$league->league_id}: {$e->getMessage()} URL: {$url}");
                $this->error("Error processing league {$league->league_id}. URL: {$url}. Error: {$e->getMessage()}");
            }

            // Advance league progress bar
            $leagueBar->advance();
        }

        $leagueBar->finish();
        $this->info("\nAll leagues processed successfully.");
    }

    public function getTech($season, $season_id, $league_id)
    {
        try {
            $url = "https://football.nowgoal29.com/jsdata/count/{$season}/playertech_{$league_id}.js";
            $client = new Client();
            $response = $client->request('GET', $url, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36',
                    'Accept'     => 'application/json, text/javascript, */*; q=0.01',
                ]
            ]);

            $data = $response->getBody()->getContents();

            preg_match('/"value":(\[\[.*?\]\])/s', $data, $matches);
            preg_match('/techCout_Player=({.*?});/s', $data, $playerMatches);

            if (!empty($matches[1]) && !empty($playerMatches[1])) {
                $valueData = json_decode($matches[1], true);
                $playerData = json_decode(str_replace("'", '"', $playerMatches[1]), true);

                foreach ($valueData as $item) {
                    $playerId = $item[0];
                    $playerName = $playerData['Pid'][$playerId][0][2] ?? null;
                    $teamId=$playerData['Pid'][$playerId][1];
                    PlayerTech::updateOrCreate(
                        [
                            'league_id' => $league_id,
                            'season_id' => $season_id,
                            'player_id' => $playerId,
                        ],
                        [
                            'play_count'     => $item[1],
                            'play_sub'       => $item[2],
                            'mins'           => $item[3],
                            'standart_goals' => $item[4],
                            'penalty_goals'  => $item[5],
                            'shots'          => $item[6],
                            'shog'           => $item[7],
                            'fauls'          => $item[8],
                            'best'           => $item[9],
                            'raiting'        => $item[10],
                            'player_name'    => $playerName,
                        ]
                    );

                    $player = \App\Models\Player::where('PlayerId', $playerId)
                        ->where('team_type', 2)
                        ->first();

                    if ($player) {
                        // Əgər player mövcuddursa və team_type 2-dirsə, yenilə
                        $player->update([
                            'name' => $playerName,
                            'teamId' => $teamId,
                        ]);
                    } else {
                        // Əgər player yoxdur və ya team_type 2 deyil, yeni player yarat
                        \App\Models\Player::create([
                            'PlayerId' => $playerId,
                            'name' => $playerName,
                            'teamId' => $teamId,
                            'team_type' => 2, // Yeni yaradılan qeyd üçün team_type dəyəri
                        ]);
                    }

                }

                $this->info("Data processed successfully for league {$league_id}, season {$season_id}.");
            } else {
                throw new \Exception("Value data or player data not found.");
            }
        } catch (\Exception $e) {
            throw new \Exception("Error in getTech for URL {$url}: " . $e->getMessage());
        }
    }
}
