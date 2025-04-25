<?php

namespace App\Console\Commands;

use App\Models\Season;
use App\Models\Standing;
use App\Models\Team;
use App\Traits\LeaguesAndGamesTrait;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GetSeasonsStandings extends Command
{
    use LeaguesAndGamesTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:getSeasonsStandings';

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
        $leagues = $this->getSelectedTypeLeagues('active');
        $total = $leagues->count(); // Ümumi liqa sayını alırıq

        foreach ($leagues as $index => $league) {
            $current = $index + 1; // Cari liqanın sırasını təyin edirik

            try {
                $this->info("{$current}/{$total} işlənir: League ID - {$league->league_id}, Season ID - {$league->lastSeason->id}");

                // Standing məlumatlarını çəkmək üçün metodu çağırırıq
                $this->getStandings(
                    $league->lastSeason->league_sub_id,
                    $league->lastSeason->title,
                    $league->lastSeason->id,
                    $league->league_id
                );

                $this->info("League ID - {$league->league_id}, Season ID - {$league->lastSeason->id} tamamlandı.");
            } catch (\Exception $e) {
                // Xəta baş verdikdə mesajı göstər və növbəti iterasiyaya davam et
                $this->error("Xəta baş verdi (League ID: {$league->league_id}): {$e->getMessage()}");
                continue;
            }
        }

        $this->info("Bütün liqalar uğurla tamamlandı.");
    }


    public function getStandings($id, $season, $season_id, $league_id)
    {
        $url = "https://football.nowgoal29.com/jsData/matchResult/{$season}/{$id}.js";
        $client = new Client();

        try {
            $response = $client->request('GET', $url, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36',
                    'Accept'     => 'application/json, text/javascript, */*; q=0.01',
                ]
            ]);

            $data = $response->getBody()->getContents();

            // Skorları çıxarmaq
            preg_match("/var\s+totalScore\s*=\s*(\[\[.*?\]\]);/i", $data, $totalScoreMatch);

            if (!empty($totalScoreMatch)) {
                $totalScoreString = $totalScoreMatch[1];
                // Tək tırnaq işarələrini iki tırnaq işarələri ilə əvəzləyin
                $totalScoreJsonString = preg_replace("/'/", '"', $totalScoreString);
                // JSON stringini arraya çevirin
                $totalScoreArray = json_decode($totalScoreJsonString, true);

                if (is_array($totalScoreArray)) {
                    try {
                        DB::transaction(function () use ($totalScoreArray, $league_id, $season_id) {
                            // 1. Müvafiq Standing qeydlərini silmək
                            Standing::where([
                                'league_id' => $league_id,
                                'season_id' => $season_id,
                            ])->delete();

                            // 2. Yeni Standing qeydlərini hazırlamaq
                            $newStandings = [];
                            foreach ($totalScoreArray as $teamData) {
                                $recentResultsIndices = array_slice($teamData, 19, 6); // 19-24 indekslərini alır

                                // Nəticələri formatlamaq
                                $recentResultsFormatted = array_map(function ($result) {
                                    return $result === 0 ? 'W' : ($result === 1 ? 'D' : 'L');
                                }, $recentResultsIndices);

                                // String formatında nəticəni birləşdiririk
                                $recentResultsString = implode(',', $recentResultsFormatted);

                                $newStandings[] = [
                                    'league_id' => $league_id,
                                    'season_id' => $season_id,
                                    'position' => $teamData[1],
                                    'team_id' => $teamData[2],
                                    'games_played' => $teamData[4],
                                    'wins' => $teamData[5],
                                    'draws' => $teamData[6],
                                    'losses' => $teamData[7],
                                    'scored' => $teamData[8],
                                    'conceded' => $teamData[9],
                                    'recent_results' => $recentResultsString,
                                    'gd' => $teamData[10],
                                    'points' => $teamData[16],
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                            }

                            // 3. Yeni Standing qeydlərini birdən əlavə etmək
                            if (!empty($newStandings)) {
                                Standing::insert($newStandings);
                            }
                        });
                    } catch (\Exception $e) {
                        // Xətanı log faylına yazın və ya digər müvafiq tədbirləri həyata keçirin
                        Log::error('Standing məlumatlarını güncəlləyərkən xəta baş verdi: ' . $e->getMessage());
                        // İstəsəniz, istifadəçiyə məlumat verə bilərsiniz
                        return response()->json(['error' => 'Məlumatları güncəlləyərkən xəta baş verdi.'], 500);
                    }
                } else {
                    Log::warning('Total score array düzgün decode olunmadı.');
                }
            }

            // Komandaları çıxarmaq
            preg_match("/var\s+arrTeam\s*=\s*(\[\[.*?\]\]);/i", $data, $teamMatch);
            if (!empty($teamMatch)) {
                $teamString = $teamMatch[1];
                $teamJsonString = preg_replace("/'/", '"', $teamString);
                $teamArray = json_decode($teamJsonString, true);

              //  foreach ($teamArray as $teamData) {
                  //  $teamName = $teamData[3];
                 //   $teamImage = $teamData[5];

                    // Komanda məlumatlarını saxlayın
                    //Team::updateOrCreate(
                    //    [
                       //     'team_id' => $teamData[0],
                        //],
                        //[
                           // 'league_id' => $league_id,
                           // 'slug' => Str::slug($teamName),
                           // 'team_name' => $teamName,
                           // 'logo' => $teamImage,
                       // ]
                  //  );
               // }
            }

            $this->info("Məlumatlar uğurla işlənildi (League ID: {$id}, Season ID: {$season_id}).");

        } catch (\Exception $e) {
            $this->error("Xəta baş verdi (League ID: {$id}, Season ID: {$season_id}): {$e->getMessage()}");
        }
    }
    public function getSpecificStandings ($id, $season_id, $season_title, $check_id)
    {
        $url = "https://football.nowgoal29.com/jsData/matchResult/{$season_title}/{$check_id}.js";
        $client = new Client();

        try {
            $response = $client->request('GET', $url, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36',
                    'Accept'     => 'application/json, text/javascript, */*; q=0.01',
                ]
            ]);

            $data = $response->getBody()->getContents();

            preg_match("/var\s+totalScore\s*=\s*(\[\[.*?\]\]);/i", $data, $totalScoreMatch);
            $totalScoreString = $totalScoreMatch[1];
            $totalScoreJsonString = preg_replace("/'/", '"', $totalScoreString);
            $totalScoreArray = json_decode($totalScoreJsonString, true);

            $standingsData = []; // Məlumatları saxlayacaq array

            // Data emalı və bazaya əlavə
            foreach ($totalScoreArray as $teamData) {
                // 19-24 arası indeksləri alıb "W", "D", "L" şəklində formatlayırıq
                $recentResultsIndices = array_slice($teamData, 19, 6); // 19-24 indekslərini alır

                // Nəticələri formatlamaq
                $recentResultsFormatted = array_map(function ($result) {
                    return $result === 0 ? 'W' : ($result === 1 ? 'D' : 'L');
                }, $recentResultsIndices);

                // String formatında nəticəni birləşdiririk
                $recentResultsString = implode(',', $recentResultsFormatted);

                // Yaradılacaq məlumat
                $standingData = [
                    'league_id' => $id,
                    'season_id' => $season_id,
                    'position' => $teamData[1],         // Tournament position
                    'team_id' => $teamData[2],          // Team ID
                    'games_played' => $teamData[4],     // Total games played
                    'wins' => $teamData[5],             // Wins
                    'draws' => $teamData[6],            // Draws
                    'losses' => $teamData[7],           // Losses
                    'scored' => $teamData[8],           // Goals scored
                    'conceded' => $teamData[9],         // Goals conceded
                    'recent_results' => $recentResultsString, // Recent results
                    'gd' => $teamData[10],              // Goal difference (from index 15)
                    'points' => $teamData[16],
                    // Points (from index 28)
                ];
                // Mövcud qeydi yoxla, yoxdursa əlavə et
                $existingStanding = \App\Models\Standing::updateOrCreate(
                    [
                        'league_id' => $id,
                        'season_id' => $season_id,
                        'team_id' => $teamData[2], // Əsas uniqlik üçün
                    ],
                    $standingData // Yenilənəcək və ya əlavə ediləcək data
                );

                if ($existingStanding->wasRecentlyCreated) {
                    $isStandingAdded = true; // Uğurlu əlavə olundu
                }
            }
            // Yalnız `standing` əlavə edildikdə `season` modelindəki `standing_gets_at` sahəsini yeniləyirik
            if ($isStandingAdded) {
                $season = Season::find($season_id);
                if ($season) {
                    $season->standing_gets_at = now()->timestamp; // `now()->timestamp` ilə unix time əlavə olunur
                    $season->save();
                    $this->info("Standing məlumatları əlavə edildi (League ID: {$id}, Season ID: {$season_id}).");
                }
            }

        } catch (\Exception $e) {
            return "Xəta baş verdi: {$e->getMessage()}";
        }
    }




    public function saveStandings($season, $season_id, $league_id)
    {
        $id = 0;
        $client = new Client();
        $validIdFound = false;
        $specificLeaguesFile = 'specific_leagues.txt';

        // Faylı yaradırıq (boş olduqda)
        if (!file_exists($specificLeaguesFile)) {
            file_put_contents($specificLeaguesFile, "");
        }

        while (!$validIdFound) {
            $url = "https://football.nowgoal29.com/jsData/matchResult/{$season}/s{$league_id}_{$id}_en.js";
            try {
                $response = $client->request('GET', $url, [
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36',
                        'Accept'     => 'application/json, text/javascript, */*; q=0.01',
                    ]
                ]);

                $data = $response->getBody()->getContents();

                // Müvafiq data olub-olmadığını yoxlayırıq
                if (preg_match("/var\\s+totalScore\\s*=\\s*(\\[\\[.*?\\]\\]);/i", $data)) {
                    // İlk düzgün cavab alındıqda fayla yeni sətrə yazırıq
                    file_put_contents($specificLeaguesFile, "{$league_id}},{$id}\n", FILE_APPEND);
                    $validIdFound = true;
                } else {
                    $id++;
                }
            } catch (\Exception $e) {
                // Xətanı ignore edirik və $id-i artırıb davam edirik
                $id++;
            }
        }

        // Düzgün id tapıldıqdan sonra davam edən kod
        preg_match("/var\\s+totalScore\\s*=\\s*(\\[\\[.*?\\]\\]);/i", $data, $totalScoreMatch);
        $totalScoreString = $totalScoreMatch[1];
        $totalScoreJsonString = preg_replace("/'/", '"', $totalScoreString);
        $totalScoreArray = json_decode($totalScoreJsonString, true);

        $standings = []; // Standings məlumatlarını toplamaq üçün dəyişən

        // Data emalı
        foreach ($totalScoreArray as $teamData) {
            // 19-24 arası indeksləri alıb "W", "D", "L" şəklində formatlayırıq
            $recentResultsIndices = array_slice($teamData, 19, 6); // 19-24 indekslərini alır

            // Nəticələri formatlamaq
            $recentResultsFormatted = array_map(function ($result) {
                return $result === 0 ? 'W' : ($result === 1 ? 'D' : 'L');
            }, $recentResultsIndices);

            // String formatında nəticəni birləşdiririk
            $recentResultsString = implode(',', $recentResultsFormatted);

            // Yaradılacaq məlumat
            $standingData = [
                'league_id' => $id,
                'season_id' => $season_id,
                'position' => $teamData[1],         // Tournament position
                'team_id' => $teamData[2],          // Team ID
                'games_played' => $teamData[4],     // Total games played
                'wins' => $teamData[5],             // Wins
                'draws' => $teamData[6],            // Draws
                'losses' => $teamData[7],           // Losses
                'scored' => $teamData[8],           // Goals scored
                'conceded' => $teamData[9],         // Goals conceded
                'recent_results' => $recentResultsString, // Recent results
                'gd' => $teamData[10],              // Goal difference (from index 15)
                'points' => $teamData[16],          // Points (from index 28)
            ];

            // Məlumatları toplamaq üçün array əlavə edirik
            $standings[] = $standingData;
        }

        // Məlumatları dd() ilə göstəririk
        dd($standings);
    }
}
