<?php

namespace App\Console\Commands;

use App\Models\Game;
use Illuminate\Console\Command;

class getPredictions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
        $this->getPredictions();
    }
    public function getPredictions()
    {
        $url = 'https://live.nowgoal29.com/gf/data/odds/en/goal8.xml';
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

        if ($httpCode === 404) {
            $this->sendSlackNotification('Get-predictions: Error: 404 Not Found.');
            die('Error fetching the data: 404 Not Found.');
        } elseif ($httpCode >= 300 && $httpCode < 400 && !empty($locationHeader)) {
            $this->sendSlackNotification('Error: Get-prediction: Document Moved. Check the new location: ' . $locationHeader);
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
            if($gameFound) {
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
}
