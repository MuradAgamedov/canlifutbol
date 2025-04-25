<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\News;
use Carbon\Carbon;

class FetchTodayNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch-today {--lang=en}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch today\'s news and save to the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = Carbon::now()->format('Y-m-d'); // Bugünkü tarix
        $lang = $this->option('lang');

        $this->info("Fetching news for today's date: {$date} and language: {$lang}");

        // API URL və headers
        $apiUrl = "https://football-news11.p.rapidapi.com/api/news-by-date?date={$date}&lang={$lang}&page=1";
        $response = Http::withHeaders([
            'x-rapidapi-host' => 'football-news11.p.rapidapi.com',
            'x-rapidapi-key' => 'a26b3970fcmshb19dcc8380b24a2p1c299djsn323b148e0b78', // Buraya RapidAPI açarınızı yazın
        ])->get($apiUrl);

        if ($response->failed()) {
            $this->error("Failed to fetch news for date: {$date}");
            return 1;
        }

        $results = $response->json()['result'];

        $addedCount = 0;
        foreach ($results as $data) {
            // ID-yə görə yoxlamaq
            $exists = News::where('id', $data['id'])->exists();

            if (!$exists) {
                News::create([
                    'id' => $data['id'],
                    'title' => $data['title'] ?? null,
                    'image' => $data['image'] ?? null,
                    'alias' => $data['alias'] ?? null,
                    'original_url' => $data['original_url'] ?? null,
                    'lang' => $data['lang'] ?? null,
                    'published_at' => $data['published_at'] ?? null,
                    'html_content' => $data['html_content'] ?? null,
                    'leagues' => $data['leagues'] ?? null,
                    'countries' => $data['countries'] ?? null,
                    'teams' => $data['teams'] ?? null,
                ]);
                $addedCount++;
            }
        }

        $this->info("{$addedCount} new articles have been saved to the database.");
        return 0;
    }
}
