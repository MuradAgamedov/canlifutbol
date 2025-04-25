<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tgames', function (Blueprint $table) {
            $table->id();
            $table->integer('game_id')->index();
            $table->string('league_name')->nullable()->index();
            $table->integer('league_id')->nullable()->index();
            $table->integer('home_club_id')->nullable()->index();
            $table->integer('away_club_id')->nullable()->index();
            $table->string('home_club_name')->nullable();
            $table->string('away_club_name')->nullable();
            $table->string('start_time')->nullable()->index();
            $table->string('last_update_time')->nullable()->index();
            $table->string('home_club_goals')->nullable()->index();
            $table->string('away_club_goals')->nullable()->index();
            $table->string('home_club_half_score')->nullable()->index();
            $table->string('away_club_half_score')->nullable()->index();
            $table->string('home_club_yellow_cards_count')->nullable()->index();
            $table->string('away_club_yellow_cards_count')->nullable()->index();
            $table->string('home_club_red_cards_count')->nullable()->index();
            $table->string('games')->nullable();
            $table->string('bet1')->index();
            $table->string('betx')->index();
            $table->string('bet2')->index();
            $table->string('status')->nullable();
            $table->string('home_club_corners_count')->nullable()->index();
            $table->string('away_club_corners_count')->nullable()->index();
            $table->string('away_club_red_cards_count')->nullable()->index();
            $table->timestamps();
            $table->integer('season_id')->nullable();
            $table->string('round')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tgames');
    }
};
