<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class getOldGameDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:getOldGameDetails';

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
        return 0;
    }

    function fetchAndParseStats($url) {
        $client = new Client();

        try {
            $response = $client->get($url);
            $html = $response->getBody()->getContents();
        } catch (\Exception $e) {
            echo 'Could not retrieve data: ' . $e->getMessage();
            return;
        }

        // HTML parse etmək üçün DOMDocument və DOMXPath
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);

        // Statistika başlıqları və keyword-lər
        $keywords = [
            'Corner Kicks', 'Corner Kicks(HT)', 'Yellow Cards', 'Red Cards', 'Shots',
            'Shots on Goal', 'Attacks', 'Dangerous Attacks', 'Shots off Goal',
            'Free Kicks', 'Possession', 'Possession(HT)', 'Passes',
            'Successful Passes', 'Fouls', 'Offsides', 'Saves', 'Assists',
            'Long Passes'
        ];

        $stats = [];

        // Bütün statistika elementlərini tapmaq
        foreach ($keywords as $keyword) {
            $query = "//span[text()='$keyword']/ancestor::li";
            $statElement = $xpath->query($query);

            if ($statElement->length > 0) {
                $homeValue = $xpath->query(".//span[@class='stat-c'][1]", $statElement[0])->item(0)->nodeValue ?? null;
                $awayValue = $xpath->query(".//span[@class='stat-c'][last()]", $statElement[0])->item(0)->nodeValue ?? null;

                // Tapılmış dəyər varsa və bu 0 deyilsə, integer-ə çeviririk
                $stats[$keyword] = [
                    'home' => $homeValue !== null ? (int)$homeValue : null,
                    'away' => $awayValue !== null ? (int)$awayValue : null
                ];
            } else {
                $stats[$keyword] = [
                    'home' => null,
                    'away' => null
                ];
            }
        }

        return $stats;
    }
    public function getGameStatisticks($id)
    {

        $url = "https://live8.nowgoal29.com/match/live-{$id}";

        $statistics = $this->fetchAndParseStats($url);

        dd([
            'home_club_corners_count' => $statistics['Corner Kicks']['home'],
            'away_club_corners_count' => $statistics['Corner Kicks']['away'],
            'home_club_corners_count_half_time' => $statistics['Corner Kicks(HT)']['home'],
            'away_club_corners_count_half_time' => $statistics['Corner Kicks(HT)']['away'],
            'home_club_yellow_cards_count' => $statistics['Yellow Cards']['home'],
            'away_club_yellow_cards_count' => $statistics['Yellow Cards']['away'],
            'home_club_red_cards_count' => $statistics['Red Cards']['home'],
            'away_club_red_cards_count' => $statistics['Red Cards']['away'],
            'home_shots' => $statistics['Shots']['home'],
            'away_shots' => $statistics['Shots']['away'],
            'home_shots_on_goal' => $statistics['Shots on Goal']['home'],
            'away_shots_on_goal' => $statistics['Shots on Goal']['away'],
            'home_attacks' => $statistics['Attacks']['home'],
            'away_attacks' => $statistics['Attacks']['away'],
            'home_dangerous_attacks' => $statistics['Dangerous Attacks']['home'],
            'away_dangerous_attacks' => $statistics['Dangerous Attacks']['away'],
            'home_shots_off_goal' => $statistics['Shots off Goal']['home'],
            'away_shots_off_goal' => $statistics['Shots off Goal']['away'],
            'home_free_kicks' => $statistics['Free Kicks']['home'],
            'away_free_kicks' => $statistics['Free Kicks']['away'],
            'home_possession' => $statistics['Possession']['home'],
            'away_possession' => $statistics['Possession']['away'],
            'home_possession_half_time' => $statistics['Possession(HT)']['home'],
            'away_possession_half_time' => $statistics['Possession(HT)']['away'],
            'home_passes' => $statistics['Passes']['home'],
            'away_passes' => $statistics['Passes']['away'],
            'home_successful_passes' => $statistics['Successful Passes']['home'],
            'away_successful_passes' => $statistics['Successful Passes']['away'],
            'home_fouls' => $statistics['Fouls']['home'],
            'away_fouls' => $statistics['Fouls']['away'],
            'home_offsides' => $statistics['Offsides']['home'],
            'away_offsides' => $statistics['Offsides']['away'],
            'home_saves' => $statistics['Saves']['home'],
            'away_saves' => $statistics['Saves']['away'],
            'home_assists' => $statistics['Assists']['home'],
            'away_assists' => $statistics['Assists']['away'],
            'home_long_passes' => $statistics['Long Passes']['home'],
            'away_long_passes' => $statistics['Long Passes']['away'],
        ]);
    }
}
