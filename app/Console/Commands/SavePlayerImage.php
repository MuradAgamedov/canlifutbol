<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use App\Models\Player;
use Carbon\Carbon;

class SavePlayerImage extends Command
{
    protected $signature = 'app:SavePlayersImages';
    protected $description = 'Fetch and store images for all players if last update is more than three days ago';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Retrieve players with `last_image_collect_time` older than 3 days or NULL
        $players = Player::where(function ($query) {
            $query->where('last_image_collect_time', '<', Carbon::now()->subDays(3))
                ->orWhereNull('last_image_collect_time');
        })->get();

        // Display the count of players with outdated images
        $count = $players->count();
        $processed = 0;
        $this->info("Found {$count} players with outdated images.\n");

        if ($count === 0) {
            $this->info('No players found with images older than three days.');
            return;
        }

        $client = new Client(['allow_redirects' => false]);
        $baseURL = 'https://football.nowgoal29.com/';

        foreach ($players as $player) {
            $processed++;

            // Update the loading message
            echo "\rProcessing player {$processed} of {$count}...";

            if (!$player->photo) {
                $this->error("\nPlayer {$player->id} does not have a photo URL.");
                continue;
            }

            // Check if the photo URL is already a full URL; if not, prepend the base URL
            $photoURL = filter_var($player->photo, FILTER_VALIDATE_URL) ? $player->photo : $baseURL . ltrim($player->photo, '/');

            // Send the request
            try {
                $response = $client->get($photoURL);
            } catch (\Exception $e) {
                $this->error("\nFailed to fetch the image for player {$player->id}: {$e->getMessage()}");
                continue;
            }

            if ($response->getStatusCode() == 200) {
                // Get the content of the response
                $imageContent = $response->getBody()->getContents();

                // Determine the image name from the URL
                $imageName = basename($photoURL);

                // Define the storage path
                $storagePath = 'images/players/' . $imageName;

                // Store the file in the public disk, creating necessary directories
                Storage::disk('public')->put($storagePath, $imageContent);

                // Update the player's image path and last image collection time

                $player->last_image_collect_time = Carbon::now();
                $player->save();
            } else {
                $this->error("\nFailed to fetch the image for player {$player->id}.");
            }
        }

        echo "\nAll players processed.\n";
    }
}
