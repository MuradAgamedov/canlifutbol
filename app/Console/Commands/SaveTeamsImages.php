<?php

namespace App\Console\Commands;

use App\Models\Team;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;

class SaveTeamsImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:SaveTeamsLogo';

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
    protected $baseUrl = "https://football.nowgoal29.com/image/team/";
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
        $teams = Team::where('id', ">", 48120)->whereNotNull('logo')
            ->where('logo', '!=', '')
            ->get();



        // Prosesin başlanğıcı
        $this->info("Imageler yüklənir, xahiş olunur gözləyin...");

        // Yükləmə barını yaratmaq üçün withProgressBar istifadə edirik
        $this->output->progressStart(count($teams));

        foreach ($teams as $team) {
            $this->info("KlubId: {$team->id} - Klub: {$team->team_name}");
            // Hər liqa üçün logo yükləyirik
            $this->downloadImageToPublicHtml($this->baseUrl . $team->logo);

            // Hər bir liqanı tamamladıqda yükləmə barını bir addım irəli aparırıq
            $this->output->progressAdvance();

            // Hər bir yükləmə sonrası 3 saniyə gözləyirik
            sleep(3);
        }

        // Yükləmə tamamlandıqda
        $this->output->progressFinish();

        $this->info("Bütün logolar uğurla yükləndi.");
    }

    public function downloadImageToPublicHtml($imageUrl)
    {
        try {
            $client = new Client([
                'allow_redirects' => false, // Yönləndirmələr deaktiv edilir
            ]);

            $response = $client->get($imageUrl);

            if ($response->getStatusCode() == 200) {
                $imageContent = $response->getBody()->getContents();
                $imageName = basename($imageUrl);

                $directory = public_path('fetchedData/images/teams/images');
                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }

                $imagePath = $directory . '/' . $imageName;
                file_put_contents($imagePath, $imageContent);

                $this->info("Şəkil uğurla yükləndi və saxlanıldı: " . $imagePath);
            } else {
                $this->warn("Şəkli yükləmək uğursuz oldu. HTTP kod: " . $response->getStatusCode() . " URL: " . $imageUrl);
            }
        } catch (RequestException $e) {
            $this->error("Sorğu zamanı xəta baş verdi: " . $e->getMessage());
            $this->error("URL: " . $imageUrl);
            if ($e->hasResponse()) {
                $this->error("HTTP Cavab: " . $e->getResponse()->getBody()->getContents());
            }
        } catch (\Exception $e) {
            $this->error("Qeyri-müəyyən xəta baş verdi: " . $e->getMessage());
            $this->error("URL: " . $imageUrl);
        }
    }

}
