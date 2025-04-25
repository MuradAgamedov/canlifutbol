<?php

namespace App\Console\Commands;

use App\Models\League;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Exception\RequestException;

class SaveLeaguesImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:getLeagueLogo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download and save league logos to storage using logo field as filename';

    /**
     * The base URL for the logos.
     *
     * @var string
     */
    protected $baseUrl = "https://football.nowgoal19.com/Image/";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $leagues = League::where('id', '>', 661)->get();


        // Prosesin başlanğıcı
        $this->info("Logolar yüklənir, xahiş olunur gözləyin...");

        // Yükləmə barını yaratmaq üçün withProgressBar istifadə edirik
        $this->output->progressStart(count($leagues));

        foreach ($leagues as $league) {
            // Hər liqa üçün logo yükləyirik
            $this->downloadImageToPublicHtml($this->baseUrl . $league->logo);

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

            // Yönləndirmələri tamamilə deaktiv edirik
            $client = new Client([
                'allow_redirects' => false, // Yönləndirmələr deaktiv edilir
            ]);

            // Sorğu göndəririk
            $response = $client->get($imageUrl);

            if ($response->getStatusCode() == 200) {
                // Cavabın məzmununu əldə edirik
                $imageContent = $response->getBody()->getContents();

                // Şəkilin adını tapırıq
                $imageName = basename($imageUrl);

                // Qovluq yolunu müəyyən edirik
                $directory = public_path('fetchedData/images/leagues/league_match');

                // Qovluğu yoxlayırıq və mövcud deyilsə yaradırıq
                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }

                // Şəkilin saxlanacağı tam yol
                $imagePath = $directory . '/' . $imageName;

                // Faylı qeyd edirik
                file_put_contents($imagePath, $imageContent);

                $this->info("Şəkil uğurla yükləndi və saxlanıldı: " . $imagePath);
            } else {
                // HTTP statusu 200 deyil
                $this->warn("Şəkli yükləmək uğursuz oldu. HTTP kod: " . $response->getStatusCode());
            }
        } catch (RequestException $e) {
            // Xətanı tuturuq və göstəririk
            $this->error("Sorğu zamanı xəta baş verdi: " . $e->getMessage());
        }
    }
}
