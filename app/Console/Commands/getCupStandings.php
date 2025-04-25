<?php

namespace App\Console\Commands;

use App\Models\Cup;
use App\Models\CupMachSeasons;
use App\Models\Season;
use App\Traits\LeaguesAndGamesTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;

class getCupStandings extends Command
{
    use LeaguesAndGamesTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:getCupStandings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch cups standings data and process it.';

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
        $currentYear = now()->year;
        $filteredSeasons = CupMachSeasons::where(function($query) use ($currentYear) {
            $query->where('title', $currentYear) // title yalnız cari ildirsə
            ->orWhere('title', 'LIKE', '%-' . $currentYear); // title iki il arasındadırsa və ikinci il cari ildir
        });

        $minIds = $filteredSeasons->selectRaw('MIN(id) as id')
            ->groupBy('league_id')
            ->pluck('id');


        $seasons = CupMachSeasons::whereIn('id', $minIds)->get();
        $total = $seasons->count(); // Ümumi liqa sayını alırıq

        foreach ($seasons as $index => $season) {
            $current = $index + 1; // Cari liqanın sırasını təyin edirik

            try {
                $this->info("{$current}/{$total} işlənir: League ID - {$season->league_id}, Season ID - {$season->id}");

                // Standing məlumatlarını çəkmək üçün metodu çağırırıq
                $this->fetchMatchData(
                    $season->title,
                    $season->league_id,
                    $season->id
                );

                $this->info("League ID - {$season->league_id}, Season ID - {$season->id} tamamlandı.");
            } catch (\Exception $e) {
                // Xəta baş verdikdə mesajı göstər və növbəti iterasiyaya davam et
                $this->error("Xəta baş verdi (League ID: {$season->league_id}): {$e->getMessage()}");
                continue;
            }
        }

        $this->info("Bütün liqalar uğurla tamamlandı.");
    }

    function fetchMatchData($season, $league_id, $season_id)
    {
        $url = "https://football.nowgoal29.com/jsData/matchResult/$season/c{$league_id}_en.js?v=20241127042054";
        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->request('GET', $url);
            $data = $response->getBody()->getContents();

            // Komandaları yoxlayıb əlavə etmək
            preg_match("/var\s+arrTeam\s*=\s*(\[\[.*?\]\]);/i", $data, $teamMatch);
            $teamMatch = $teamMatch[1];
            $teamMatch = json_decode(str_replace("'", '"', $teamMatch), true);

            foreach ($teamMatch as $team) {
                $teamId = $team[0];
                $teamName = $team[3];

                // Komanda yoxlaması və əlavə
                \App\Models\Team::updateOrCreate(
                    ['team_id' => $teamId], // ID ilə uyğunluq axtar
                    ['team_name' => $teamName, 'league_id' => $league_id]
                );
            }

            // Qrup və nəticələri işləyərək CupMachSeasons modelinə əlavə etmək
            preg_match_all('/jh\["(S.*?)"\]\s*=\s*(\[.*?\]);/s', $data, $matches);

            DB::transaction(function () use ($matches, $league_id, $season_id) {
                foreach ($matches[1] as $index => $group) {
                    // JSON çevrilməsi
                    $arrays = json_decode(str_replace("'", '"', $matches[2][$index]), true);

                    if (is_array($arrays)) {
                        // Mövcud qrup üçün bütün qeydləri silmək
                        \App\Models\CupStandings::where([
                            'league_id' => $league_id,
                            'season_id' => $season_id,
                            'group' => $group,
                        ])->delete();

                        // Yeni qeydlərin hazırlanması
                        $newStandings = [];
                        foreach ($arrays as $array) {
                            $newStandings[] = [
                                'league_id' => $league_id,
                                'season_id' => $season_id,
                                'group' => $group,
                                'team_id' => $array[1],
                                'rank' =>  $array[0],
                                'total' => $array[2],
                                'win' => $array[3],
                                'draw' => $array[4],
                                'loses' => $array[5],
                                'scored' => $array[6],
                                'conceded' => $array[7],
                                'diff' => $array[8],
                                'points' => $array[9],
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }

                        // Birdən çox qeydi eyni anda əlavə etmək
                        if (!empty($newStandings)) {
                            \App\Models\CupStandings::insert($newStandings);
                        }
                    }
                }
            });
        } catch (\Exception $e) {
            $this->error("Error fetching data for season: $season, league: $league_id. Error: " . $e->getMessage());
        }
    }
}
