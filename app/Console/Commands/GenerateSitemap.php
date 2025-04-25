<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\Game;
use App\Models\Team;
use App\Models\Player;
use App\Models\League;
use App\Models\Cup;
use App\Models\UserBlogPost;
use App\Models\ForumPost;

class GenerateSitemap extends Command
{
    protected $signature = 'generate:sitemap {domain}';
    protected $description = 'Generates sitemap with progress bar and full output';

    private $limit = 20000;

    public function handle()
    {
        $domain = rtrim($this->argument('domain'), '/');

        // ✅ Protokolu yoxla və əlavə et
        if (!Str::startsWith($domain, ['http://', 'https://'])) {
            $domain = 'https://' . $domain;
        }

        $folder = str_replace(['https://', 'http://', '.', '/'], '', $domain);

        $this->info("🔧 Sitemap yaradılır: $domain");

        $baseDir = public_path('sitemaps/' . $folder);
        if (!file_exists($baseDir)) {
            mkdir($baseDir, 0755, true);
        }

        $this->info("➡️  Statik linklər yüklənir...");
        $now = now()->toW3cString();
        $links = collect([
            '/',
            '/livescore',
            '/today',
            '/yesterday',
            '/tomorrow',
            '/finished',
            '/not-started',
            '/fixtures',
            '/predictions',
            '/forum',
            '/blog',
            '/leagues',
            '/amateur/games/games',
            '/amateur/join-links/links'
        ])->map(fn($path) => [
            'url' => $domain . $path,
            'changefreq' => 'daily',
            'priority' => '0.8',
            'lastmod' => $now
        ])->toArray();

        $this->info("➡️  Forum postlar yüklənir...");
        $forum = ForumPost::where('status', 1)->get()->map(fn($f) => [
            'url' => $domain . '/forum/' . $f->id . '/' . Str::slug($f->title),
            'changefreq' => 'daily',
            'priority' => '0.8',
            'lastmod' => Carbon::createFromTimestamp($f->time)->toW3cString()
        ])->toArray();

        $this->info("➡️  Blog postlar yüklənir...");
        $blog = UserBlogPost::where('status', 1)->get()->map(fn($b) => [
            'url' => $domain . '/blog/' . $b->id . '/' . Str::slug($b->title),
            'changefreq' => 'daily',
            'priority' => '0.8',
            'lastmod' => Carbon::createFromTimestamp($b->time)->toW3cString()
        ])->toArray();

        $this->info("➡️  Oyunlar yüklənir...");
        $game = Game::get()->flatMap(function ($g) use ($domain, $now) {
            $slug = Str::slug($g->home_club_name . '-' . $g->away_club_name);
            return [
                ['url' => "$domain/game-info/{$g->game_id}/$slug", 'changefreq' => 'daily', 'priority' => '0.85', 'lastmod' => $now],
                ['url' => "$domain/prediction/{$g->game_id}/$slug", 'changefreq' => 'daily', 'priority' => '0.80', 'lastmod' => $now],
                ['url' => "$domain/h2h/{$g->game_id}/$slug", 'changefreq' => 'weekly', 'priority' => '0.75', 'lastmod' => $now],
                ['url' => "$domain/h2h/home_team_matches/{$g->game_id}/$slug", 'changefreq' => 'weekly', 'priority' => '0.65', 'lastmod' => $now],
                ['url' => "$domain/h2h/away_team_matches/{$g->game_id}/$slug", 'changefreq' => 'weekly', 'priority' => '0.65', 'lastmod' => $now],
            ];
        })->toArray();

        $this->info("➡️  Komandalar və matçlar yüklənir...");
        $team = Team::get()->flatMap(function ($t) use ($domain, $now) {
            $slug = Str::slug($t->team_name);
            return [
                ['url' => "$domain/team/{$t->team_id}/$slug", 'changefreq' => 'weekly', 'priority' => '0.70', 'lastmod' => $now],
                ['url' => "$domain/team/{$t->team_id}/$slug/matches", 'changefreq' => 'daily', 'priority' => '0.80', 'lastmod' => $now],
            ];
        })->toArray();

        $this->info("➡️  Oyunçular yüklənir...");
        $players = Player::get()->map(fn($p) => [
            'url' => "$domain/player/{$p->playerId}/" . Str::slug($p->name),
            'changefreq' => 'monthly',
            'priority' => '0.60',
            'lastmod' => $now
        ])->toArray();

        $this->info("➡️  Liqalar yüklənir...");
        $leagues = League::with('seasons')->get()->flatMap(function ($l) use ($domain, $now) {
            return $l->seasons->flatMap(function ($s) use ($l, $domain, $now) {
                return [
                    ['url' => "$domain/leagues/standings/{$l->league_id}?season={$s->id}", 'changefreq' => 'daily', 'priority' => '0.80', 'lastmod' => $now],
                    ['url' => "$domain/leagues/games/{$l->league_id}?season={$s->id}", 'changefreq' => 'daily', 'priority' => '0.75', 'lastmod' => $now],
                    ['url' => "$domain/leagues/tech_stats/{$l->league_id}?season={$s->id}", 'changefreq' => 'weekly', 'priority' => '0.70', 'lastmod' => $now]
                ];
            });
        })->toArray();

        $this->info("➡️  Kuboklar yüklənir...");
        $cups = Cup::with('seasons', 'oldSeasons')->get()->flatMap(function ($c) use ($domain, $now) {
            $arr = [];
            if ($c->seasons) {
                $arr[] = ['url' => "$domain/cups/standings/{$c->seasons->id}/" . urlencode($c->seasons->title) . "/{$c->league_id}", 'changefreq' => 'daily', 'priority' => '0.80', 'lastmod' => $now];
            }
            foreach ($c->oldSeasons as $s) {
                $arr[] = ['url' => "$domain/cups/games/{$c->league_id}/" . urlencode($s->title) . "?season={$s->id}", 'changefreq' => 'daily', 'priority' => '0.75', 'lastmod' => $now];
            }
            return $arr;
        })->toArray();

        $all = array_merge($links, $forum, $blog, $game, $team, $players, $leagues, $cups);
        $all = collect($all)->unique('url')->values()->toArray();

        $total = count($all);
        $sitemapCount = ceil($total / $this->limit);
        $sitemaps = [];

        $this->info("📦 Toplam $total link tapıldı. Fayllar yaradılır...");
        $this->output->progressStart($total);

        for ($i = 0; $i < $sitemapCount; $i++) {
            $chunk = array_slice($all, $i * $this->limit, $this->limit);
            $filename = 'sitemap' . ($i + 1) . '.xml';
            $filePath = $baseDir . '/' . $filename;

            $this->generateSitemapFile($chunk, $filePath);
            $sitemaps[] = ['loc' => $domain . '/sitemaps/' . $folder . '/' . $filename, 'lastmod' => now()->toW3cString()];
            $this->output->progressAdvance(count($chunk));
        }

        $this->generateSitemapIndex($sitemaps, $baseDir);
        $this->output->progressFinish();
        $this->info('✅ Sitemap və index faylları uğurla yaradıldı!');
    }

    private function generateSitemapFile(array $links, string $filePath)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $urlset = $dom->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $dom->appendChild($urlset);

        foreach ($links as $link) {
            $url = $dom->createElement('url');
            $url->appendChild($dom->createElement('loc', $link['url']));
            $url->appendChild($dom->createElement('lastmod', $link['lastmod']));
            $url->appendChild($dom->createElement('changefreq', $link['changefreq']));
            $url->appendChild($dom->createElement('priority', $link['priority']));
            $urlset->appendChild($url);
        }

        file_put_contents($filePath, $dom->saveXML());
    }

    private function generateSitemapIndex(array $sitemaps, string $baseDir)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $sitemapindex = $dom->createElement('sitemapindex');
        $sitemapindex->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $dom->appendChild($sitemapindex);

        foreach ($sitemaps as $sitemap) {
            $sitemapElement = $dom->createElement('sitemap');
            $sitemapElement->appendChild($dom->createElement('loc', $sitemap['loc']));
            $sitemapElement->appendChild($dom->createElement('lastmod', $sitemap['lastmod']));
            $sitemapindex->appendChild($sitemapElement);
        }

        file_put_contents($baseDir . '/sitemap_index.xml', $dom->saveXML());
    }
}
