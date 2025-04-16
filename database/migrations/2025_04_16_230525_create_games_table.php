<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id');
            $table->string('league_name')->nullable();
            $table->unsignedBigInteger('league_id')->nullable();
            $table->unsignedBigInteger('home_club_id')->nullable();
            $table->unsignedBigInteger('away_club_id')->nullable();
            $table->string('home_club_name')->nullable();
            $table->string('away_club_name')->nullable();
            $table->string('start_time')->nullable();
            $table->string('last_update_time')->nullable();
            $table->integer('home_club_goals')->nullable();
            $table->integer('away_club_goals')->nullable();
            $table->string('home_club_half_score')->nullable();
            $table->string('away_club_half_score')->nullable();
            $table->integer('home_club_yellow_cards_count')->nullable();
            $table->integer('away_club_yellow_cards_count')->nullable();
            $table->integer('home_club_red_cards_count')->nullable();
            $table->string('games')->nullable();
            $table->string('bet1');
            $table->string('betx');
            $table->string('bet2');
            $table->tinyInteger('status')->nullable();
            $table->integer('home_club_corners_count')->nullable();
            $table->integer('away_club_corners_count')->nullable();
            $table->integer('away_club_red_cards_count')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('season_id')->nullable();

            // Indexlər
            $table->index('game_id');
            $table->index('league_name');
            $table->index('league_id');
            $table->index('home_club_id');
            $table->index('away_club_id');
            $table->index('home_club_name');
            $table->index('away_club_name');
            $table->index('start_time');
            $table->index('last_update_time');
            $table->index('home_club_goals');
            $table->index('away_club_goals');
            $table->index('home_club_half_score');
            $table->index('away_club_half_score');
            $table->index('home_club_yellow_cards_count');
            $table->index('away_club_yellow_cards_count');
            $table->index('home_club_red_cards_count');
            $table->index('bet1');
            $table->index('betx');
            $table->index('bet2');
            $table->index('status');
            $table->index('home_club_corners_count');
            $table->index('away_club_corners_count');
            $table->index('away_club_red_cards_count');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
