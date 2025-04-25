<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\News;

class FetchHtmlContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch-html';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch HTML content for each News by ID and update the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Bütün xəbərləri götür
        $newsItems = News::all();

        if ($newsItems->isEmpty()) {
            $this->info('No news items found in the database.');
            return 0;
        }

        $this->info("Found {$newsItems->count()} news items. Fetching HTML content...");

        $updatedCount = 0;

        foreach ($newsItems as $news) {
            $apiUrl = "https://football-news11.p.rapidapi.com/api/news-content?id={$news->id}";
            $response = Http::withHeaders([
                'x-rapidapi-host' => 'football-news11.p.rapidapi.com',
                'x-rapidapi-key' => 'a26b3970fcmshb19dcc8380b24a2p1c299djsn323b148e0b78', // Buraya RapidAPI açarınızı yazın
            ])->get($apiUrl);

            if ($response->failed()) {
                $this->error("Failed to fetch HTML content for news ID: {$news->id}");
                continue;
            }

            $data = $response->json()['result'] ?? null;

            if (isset($data['html_content'])) {
                // `html_content` sahəsini yenilə
                $news->update(['html_content' => $data['html_content']]);
                $updatedCount++;
                $this->info("HTML content updated for news ID: {$news->id}");
            } else {
                $this->error("No HTML content found for news ID: {$news->id}");
            }
        }

        $this->info("HTML content updated for {$updatedCount} news items.");
        return 0;
    }
}
