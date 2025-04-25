<?php

namespace App\Console\Commands;

use App\Models\League;
use App\Models\Team;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GetNonTeams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:getNonTeams';

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


    public function getTeams()
    {
        $leagues = League::whereIn('country_id', [0, 1,2, 56, 77, 106, 108])->
        whereIn('league_id', [83, 346, 264,219, 81, 704, 58, 1876, 186, 2216])
            ->where('last_collect_time', null)
            ->orWhere('league_name', 'LIKE', "%cups%")
            ->orWhere('league_name', 'LIKE', "%FA%")
            ->get();

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

                $jsonString = preg_replace("/'/", '"', $arrTeamString);
                $arrTeam = explode('],',$arrTeamString);

                // Progress bar for teams
                // Progress bar for teams
                $this->info("Fetching teams for league ID {$leagueId}...");
                $teamBar = $this->output->createProgressBar(count($arrTeam));
                $teamBar->start();


                foreach ($arrTeam as $team) {
                    $t = explode(',', $team);

                    $findedTeam = Team::where("team_id", str_replace('[', '', $t[0]))->where('team_type', 1)->first();

                    if ($findedTeam) {
                        $findedTeam->update([
                            'team_id' => str_replace('[', '', $t[0]),
                            'league_id' => $leagueId,
                            'slug' => Str::slug(str_replace("'", '', $t[3])),
                            'team_name' => str_replace("'", '', $t[3]),
                            'logo' => str_replace(']]', '', str_replace("'", '', $t[5])),
                            'team_type' => 1
                        ]);
                    } else {
                        Team::create([
                            'team_id' => str_replace('[', '', $t[0]),
                            'league_id' => $leagueId,
                            'slug' => Str::slug(str_replace("'", '', $t[3])),
                            'team_name' => str_replace("'", '', $t[3]),
                            'logo' => str_replace(']]', '', str_replace("'", '', $t[5])),
                            'team_type' => 1
                        ]);
                    }
                    $teamBar->advance(); // Advance the progress bar for teams
                }

                $teamBar->finish(); // Finish the team progress bar
                $this->newLine(); // Add a newline after the team progress bar
            }
        } catch (\Exception $e) {

        }
    }
}
