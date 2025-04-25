<?php
namespace App\Console\Commands;

use App\Models\CountryTeam;
use App\Models\Player;
use App\Models\Team;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class getPlayers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:getPlayers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect and update players for all teams.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->getPlayers();
    }

    public function getPlayers()
    {
        $teams = Team::whereNotIn('team_id', CountryTeam::pluck('team_id'))->where('id', ">", 54233)->get();


        // Start the progress bar
        $this->output->progressStart(count($teams));

        foreach ($teams as $team) {
            $team = Team::where('team_id', $team->team_id)->first();
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

                // Update or create player
                if ($player) {
                    $player->update([
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
                        'team_type' => $type
                    ]);
                } else {
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
                        'team_type' => $type
                    ]);
                }
            }
        } catch (\Exception $e) {
            $this->error("Error fetching player data for team ID {$id}: " . $e->getMessage());
        }
    }

    function getPlayerImageUrl($id)
    {
        $url = 'https://football.nowgoal19.com/team/player/'.$id;
        $client = new Client();

        try {
            // Sadə sorğu ilə yalnız HTML əldə etmək üçün başlıqları sadələşdiririk
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Accept' => 'text/html',
                    'User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                $html = $response->getBody()->getContents();
                $crawler = new Crawler($html);
                // Şəkil URL-ni əldə edirik
                $imageUrl = $crawler->filter('img#imgPhoto')->attr('src');

                // Şəkil URL varsa, geri qaytarırıq
                return $imageUrl ? $imageUrl : null;
            }
        } catch (\Exception $e) {
            $this->error("Error fetching image for player ID {$id}: " . $e->getMessage());
        }

        // Hər hansı bir səhv və ya şəkil tapılmadıqda null qaytarırıq
        return null;
    }

}
