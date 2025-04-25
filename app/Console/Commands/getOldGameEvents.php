<?php

namespace App\Console\Commands;

use App\Models\CupGame;
use App\Models\Game;
use App\Models\Event;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class getOldGameEvents extends Command
{
    protected $signature = 'app:getOldGameEvents';
    protected $description = 'Fetch old game events and update database';

    public function handle()
    {
        $games = CupGame::where('id', ">", 3464)->get();
        $totalGames = $games->count();

        if ($totalGames === 0) {
            $this->info('No games found.');
            return 0;
        }

        $this->info("Found {$totalGames} games. Processing...");

        $progressBar = $this->output->createProgressBar($totalGames);
        $progressBar->start();

        foreach ($games as $game) {
            $this->getGameDetails($game->game_id, $game->id);
            sleep(5);
            $game->update(['addedd_add_old' => now()]);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->info("\nAll games processed successfully!");

        return 0;
    }

    public function getGameDetails($id, $gI)
    {
        $retryCount = 0; // Təkrar sayını izləmək üçün dəyişən
        $maxRetries = 10; // Maksimum təkrar sayı
        $waitTime = 2; // Hər təkrar arasında gözləmə müddəti (saniyə ilə)

        while ($retryCount < $maxRetries) {
            try {
                $url = "https://live.nowgoal19.com/match/live-{$id}";
                $client = new Client();
                $response = $client->request('GET', $url);
                $body = $response->getBody()->getContents();

                $doc = new \DOMDocument();
                @$doc->loadHTML($body);
                $xpath = new \DOMXPath($doc);

                $homeTeamNode = $xpath->query("//div[@class='home']/i[@class='icon iconfont icon-font-collect-off']")->item(0);
                $awayTeamNode = $xpath->query("//div[@class='guest']/i[@class='icon iconfont icon-font-collect-off']")->item(0);
                $homeTeamId = $homeTeamNode ? $homeTeamNode->getAttribute('data-tid') : null;
                $awayTeamId = $awayTeamNode ? $awayTeamNode->getAttribute('data-tid') : null;

                $rows = $xpath->query("//table[@class='team-table-other ky']//tr[not(contains(@class, 'ky_tit'))]");

                $events = [];

                foreach ($rows as $row) {
                    $minuteNode = $xpath->query(".//td[3]/b", $row)->item(0);
                    $minute = $minuteNode ? $minuteNode->textContent : null;

                    // Process Home Events
                    $this->processEvent($xpath, $row, $events, $minute, $homeTeamId, ".//td[2]/img", ".//td[1]", $id);

                    // Process Away Events
                    $this->processEvent($xpath, $row, $events, $minute, $awayTeamId, ".//td[4]/img", ".//td[5]", $id);
                }

                Event::where('game_id', $id)->delete();
                foreach ($events as $event) {
                    Event::create($event);
                }

                $this->info("Oyun hadisələri uğurla əlavə edildi: Oyun ID-si - {$gI}");

                // Əgər uğurlu olarsa, dövrü dayandır
                return;
            } catch (\Exception $e) {
                $retryCount++; // Təkrar sayını artır
                $this->error("Xəta baş verdi (ID: {$id}), Təkrar cəhdi: {$retryCount}/{$maxRetries}. Xəta mesajı: " . $e->getMessage());

                // Əgər maksimum təkrar sayı keçilibsə, dövrü dayandır
                if ($retryCount >= $maxRetries) {
                    $this->error("Oyun ID {$id} üçün maksimum təkrar cəhd sayı keçildi. Növbəti oyuna keçir.");
                    return;
                }

                // Gözləmə müddəti
                sleep($waitTime);
            }
        }
    }



    private function processEvent($xpath, $row, &$events, $minute, $teamId, $eventTypeXPath, $playerXPath, $game_id)
    {


        $eventTypeNode = $xpath->query(".//span[@class='icontips']", $row)->item(0);
        $eventType = $eventTypeNode ? $eventTypeNode->getAttribute('txt') : null;

        $playerNode = $xpath->query($playerXPath, $row)->item(0);

        if ($playerNode && $eventType) {
            $playerNameNode = $xpath->query(".//a", $playerNode)->item(0);
            $playerName = $playerNameNode ? $playerNameNode->textContent : null;
            $playerId = $playerNameNode ? $this->extractPlayerId($playerNameNode->getAttribute('href')) : null;
            $assistPlayer = null;
            $assistPlayerId = null;

            if ($eventType == 'Substitution') {
                $subInNode = $xpath->query(".//img[@alt='Sub in']/following-sibling::a", $playerNode)->item(0);
                $subOutNode = $xpath->query(".//img[@alt='Sub out']/following-sibling::a", $playerNode)->item(0);

                $playerName = $subInNode ? $subInNode->textContent : null;
                $playerId = $subInNode ? $this->extractPlayerId($subInNode->getAttribute('href')) : null;
                $assistPlayer = $subOutNode ? $subOutNode->textContent : null;
                $assistPlayerId = $subOutNode ? $this->extractPlayerId($subOutNode->getAttribute('href')) : null;
            } elseif ($eventType == 'Goal') {
                $assistNode = $xpath->query(".//span/a", $playerNode)->item(0);
                $assistPlayer = $assistNode ? $assistNode->textContent : null;
                $assistPlayerId = $assistNode ? $this->extractPlayerId($assistNode->getAttribute('href')) : null;
            } elseif ($eventType == 'Yellow Card' || $eventType == 'Red Card') {
                $playerName = $playerNameNode ? $playerNameNode->textContent : null;
                $playerId = $playerNameNode ? $this->extractPlayerId($playerNameNode->getAttribute('href')) : null;
            }

            $events[] = [
                'game_id' => $game_id, // Corrected to link the event to the team
                'minute' => $minute,
                'player_name' => $playerName,
                'player_id' => $playerId,
                'event_type' => $eventType,
                'assist_player' => $assistPlayer,
                'assist_palyer_id' => intval($assistPlayerId),
                'team_id' => $teamId,
            ];
        }
    }

    private function extractPlayerId($url)
    {
        preg_match('/player\/(\d+)/', $url, $matches);
        return $matches[1] ?? null;
    }
}
