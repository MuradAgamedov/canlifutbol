<?php

namespace App\Console\Commands;

use App\Models\Player;
use Illuminate\Console\Command;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;

class getPlayersImages extends Command
{
    protected $signature = 'app:getPlayersImages';
    protected $description = 'Get images for players and update the database.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $players = Player::whereNull('last_image_collect_time')
            ->get();

        if ($players->isEmpty()) {
            $this->info('No players found with null last_image_collect_time and photo.');
            return 0;
        }

        $this->info('Fetching images for players...');
        $this->output->progressStart($players->count());

        foreach ($players as $player) {
            $this->getImages($player->playerId);
            $this->output->progressAdvance();
            sleep(3); // Her sorgu arasında 3 saniye bekle
        }

        $this->output->progressFinish();
        $this->info('All players images have been updated.');
        return 0;
    }

    public function getImages($id)
    {
        $client = new Client([
            'timeout' => 20, // 20 saniye bekle
            'http_errors' => false, // 4xx hatalarında exception atma
        ]);

        $retryCount = 0;
        $maxRetries = 3; // 3 kez deneme yap
        $retry = true;

        while ($retry && $retryCount < $maxRetries) {
            try {
                $response = $client->request('GET', 'https://football.nowgoal29.com/team/player/' . $id);

                if ($response->getStatusCode() == 404) {
                    $player = Player::where('playerId', $id)->first();
                    $player->update([
                        'last_image_collect_time' => now(),
                    ]);
                    $this->error("Player with ID: $id not found (404). Moving to next player.");
                    return;
                }

                if ($response->getStatusCode() == 200) {
                    $html = $response->getBody()->getContents();
                    $dom = new \DOMDocument();
                    libxml_use_internal_errors(true);
                    $dom->loadHTML($html);
                    libxml_clear_errors();
                    $xpath = new \DOMXPath($dom);
                    $imageNodes = $xpath->query('//*[@id="imgPhoto"]');

                    if ($imageNodes->length > 0) {
                        $src = $imageNodes->item(0)->getAttribute('src');
                        $player = Player::where('playerId', $id)->first();
                        $player->update([
                            'photo' => $src,
                            'last_image_collect_time' => now(),
                        ]);
                        $retry = false; // Başarıyla tamamlandığında döngüden çık
                    } else {
                        $this->error("No image found for player ID: $id.");
                        $retry = false; // Resim yoksa tekrar deneme
                    }
                }
            } catch (ConnectException $e) {
                // Zaman aşımı hatasını yakala ve tekrar dene
                if (strpos($e->getMessage(), 'cURL error 28') !== false) {
                    $retryCount++;
                    $this->error("Request for player ID: $id timed out (Attempt $retryCount). Retrying...");
                    sleep(5); // Her denemeden sonra 5 saniye bekle
                } else {
                    $this->error("Request for player ID: $id failed. Error: " . $e->getMessage());
                    $retry = false; // Diğer hatalarda dur
                }

                if ($retryCount >= $maxRetries) {

                    $this->error("Max retries reached for player ID: $id. Moving to next player.");
                    $retry = false;
                }
            } catch (RequestException $e) {
                $this->error("Request for player ID: $id failed. Error: " . $e->getMessage());
                $retry = false; // Diğer hatalarda tekrar deneme
            }
        }
    }
}
