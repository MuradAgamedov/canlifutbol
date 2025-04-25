<?php

namespace App\Console\Commands;

use App\Models\Cup;
use App\Models\CupGame;
use App\Models\CupMachSeasons;
use App\Models\Season;
use App\Models\Team;
use Illuminate\Console\Command;

class getCupGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:getCupGame';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and update cups games';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currentYear = now()->year;
        // Sezonları filtr edin: Cari il və ya cari ili əhatə edən başlıqlar
        $seasons = CupMachSeasons::where(function ($query) use ($currentYear) {
            $query->where('title', $currentYear) // Mövsüm cari ilə bərabərdirsə
            ->orWhere('title', 'LIKE', '%-' . $currentYear); // Mövsüm iki il arasını əhatə edirsə
        })
            ->get();
        $totalSeasons = $seasons->count();
        $processedSeasons = 0;

        foreach ($seasons as $season) {
            $processedSeasons++;
            $this->info("Processing season $processedSeasons of $totalSeasons: {$season->title}");

            // `games_get_add` sahəsini `now()` ilə yenilə
            $season->update(['games_get_add' => now()]);

            // Mövsümə aid oyun məlumatlarını al
            $this->fetchMatchData($season->title, $season->league_id, $season->id);

            $this->info("Completed season $processedSeasons of $totalSeasons. Season id is {$season->id} season is {$season->title} league: {$season->league_id}");
        }

        $this->info("All seasons processed.");
        CupGame::whereNull("home_club_goals")
            ->orWhereNull("away_club_goals")
            ->delete();
    }



    function fetchMatchData($season, $id, $season_id) {
        $url = "https://football.nowgoal29.com/jsData/matchResult/$season/c{$id}_en.js?v=20241127235232";
        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->request('GET', $url);
            $data = $response->getBody()->getContents();

            // JSON-dan qrupları və oyunları çıxarırıq
            preg_match_all('/jh\["([G]\d+A?)"\]\s*=\s*(\[.*?\]);/s', $data, $matches);
            preg_match('/var arrCupKind = (\[.*?\]);/s', $data, $cupKindMatches);
            $explodedKinds = explode('],', $cupKindMatches[1]);
            $d = [];
            foreach ($explodedKinds as $kind) {
                $r = str_replace('[', '', $kind);
                $r = str_replace(']', '', $r);
                $dr = explode(',', $r);
                $d[$dr[0]] = $dr;
            }



            $f = array_reverse($matches[2]); // Oyun siyahılarını tərsinə çeviririk
            $rg = array_reverse($matches[1]); // Qrupları tərsinə çeviririk

            foreach ($f as $index => $groups) {
                $groupName = $rg[$index] ?? null;  // Hər bir oyun qrupu üçün doğru group dəyərini alırıq


                $groupName = str_replace(["G", "S", "A", "'"], '', $d[str_replace(["G", "S", "A"], '', $groupName)][4]);


                // Oyunları bölərək əldə edirik
                $groupFor = preg_split('/\],\s*\[/', $groups);

                foreach ($groupFor as $group) {
                    // Həm `,` həm də `,[` görə parçala və ilk elementi götür
                    $oneGame = preg_split('/,\[/', $group);
                    $oneGame = array_reverse($oneGame)[0];

                    $oneGame = explode(',', str_replace('[', '', $oneGame));
                    $gg = $rg[$index];
                    // Məlumatları təşkil etmək
                    $this->info($gg);
                    $gameData = [
                        'season_id' => $season_id,
                        'group' => $groupName, // Artıq düzgün qrup adını götürürük
                        'league_name' => Cup::where('league_id', $id)->first()->league_name,
                        'game_id' => str_replace(['"', "'"], '', $oneGame[0] ?? null),
                        'league_id' => str_replace(['"', "'"], '', $oneGame[1] ?? null),
                        'status' => str_replace(['"', "'"], '', $oneGame[2] ?? null),
                        'start_time' => isset($oneGame[3]) ? str_replace(['"', "'"], '', $oneGame[3]) : null,
                        'home_club_id' => str_replace(['"', "'"], '', $oneGame[4] ?? null),
                        'away_club_id' => str_replace(['"', "'"], '', $oneGame[5] ?? null),
                        "home_club_name" => optional(
                            Team::where('team_id', str_replace(['"', "'"], '', $oneGame[4] ?? null))->first()
                        )->team_name,
                        "away_club_name" => optional(
                            Team::where('team_id', str_replace(['"', "'"], '', $oneGame[5] ?? null))->first()
                        )->team_name,
                        'home_club_goals' => isset($oneGame[6]) ? str_replace(['"', "'"], '', explode('-', $oneGame[6])[0] ?? null) : null,
                        'away_club_goals' => isset($oneGame[6]) ? str_replace(['"', "'"], '', explode('-', $oneGame[6])[1] ?? null) : null,
                        'home_club_half_score' => isset($oneGame[7]) ? str_replace(['"', "'"], '', explode('-', $oneGame[7])[0] ?? null) : null,
                        'away_club_half_score' => isset($oneGame[7]) ? str_replace(['"', "'"], '', explode('-', $oneGame[7])[1] ?? null) : null,
                    ];

                    // Oyun məlumatlarını bazaya əlavə et və ya yenilə
                    $existingGame = CupGame::where('game_id', $gameData['game_id'])->first();

                    if ($existingGame) {
                        $existingGame->update([
                            'group' => $groupName,
                            'season_id' => $gameData['season_id'],
                            'league_id' => $gameData['league_id'],
                            'status' => $gameData['status'],
                            'start_time' => $gameData['start_time'],
                            'home_club_id' => $gameData['home_club_id'],
                            'away_club_id' => $gameData['away_club_id'],
                            'home_club_goals' => $gameData['home_club_goals'],
                            'away_club_goals' => $gameData['away_club_goals'],
                            'home_club_half_score' => $gameData['home_club_half_score'],
                            'away_club_half_score' => $gameData['away_club_half_score'],
                        ]);
                    } else {
                        CupGame::create($gameData);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->error("Error fetching data for season $season: " . $e->getMessage());
        }
    }


}
