<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tgame;
use App\Models\Game;

class SyncGames extends Command
{
    protected $signature = 'app:sygames';
    protected $description = 'Tgame modelindən oyunları Game modelinə sinxronlaşdırır';

    public function handle()
    {
        // Tgame modelindən bütün oyunları al
        $tgames = Tgame::all();

        // Toplam oyun sayını götür
        $totalGames = $tgames->count();

        // Əgər heç bir oyun yoxdursa, prosesi bitir
        if ($totalGames === 0) {
            $this->info('Sinxronizasiya üçün heç bir oyun tapılmadı.');
            return;
        }

        // Yükləmə vəziyyətini göstərən proqres bar ilə işləyirik
        $this->withProgressBar($tgames, function ($tgame) {
            // Game cədvəlində həmin game_id ilə oyun varmı?
            $exists = Game::where('game_id', $tgame->game_id)->exists();

            if (!$exists) {
                // Mövcud deyilsə, yeni qeyd əlavə et
                Game::create([
                    'game_id' => $tgame->game_id,
                    'league_name' => $tgame->league_name,
                    'league_id' => $tgame->league_id,
                    'home_club_id' => $tgame->home_club_id,
                    'away_club_id' => $tgame->away_club_id,
                    'home_club_name' => $tgame->home_club_name,
                    'away_club_name' => $tgame->away_club_name,
                    'start_time' => $tgame->start_time,
                    'last_update_time' => $tgame->last_update_time,
                    'home_club_goals' => $tgame->home_club_goals,
                    'away_club_goals' => $tgame->away_club_goals,
                    'home_club_half_score' => $tgame->home_club_half_score,
                    'away_club_half_score' => $tgame->away_club_half_score,
                    'home_club_yellow_cards_count' => $tgame->home_club_yellow_cards_count,
                    'away_club_yellow_cards_count' => $tgame->away_club_yellow_cards_count,
                    'home_club_red_cards_count' => $tgame->home_club_red_cards_count,
                    'away_club_red_cards_count' => $tgame->away_club_red_cards_count,
                    'games' => $tgame->games,
                    'bet1' => $tgame->bet1,
                    'betx' => $tgame->betx,
                    'bet2' => $tgame->bet2,
                    'status' => $tgame->status,
                    'home_club_corners_count' => $tgame->home_club_corners_count,
                    'away_club_corners_count' => $tgame->away_club_corners_count,
                    'created_at' => $tgame->created_at,
                    'updated_at' => $tgame->updated_at,
                    'season_id' => $tgame->season_id,
                ]);
            }
        });

        $this->newLine(); // Proqres bar bitdikdən sonra yeni sətrə keç
        $this->info('Sinxronizasiya tamamlandı!');
    }
}
