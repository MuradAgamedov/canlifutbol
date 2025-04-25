<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\Sitemap;

class SitemapGeneratorCommand extends Command // <-- bu adı saxla, çünki faylın adı budur
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate and update sitemap XML files';

    public function handle()
    {
        ini_set('max_execution_time', 0);
        $this->info('Sitemap generation started...');

        try {
            $sitemap = new Sitemap();
            $sitemap->update();
            $this->info('Sitemap generation completed successfully!');
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
