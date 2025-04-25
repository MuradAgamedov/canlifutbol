<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('standings', function (Blueprint $table) {
            $table->id();

            $table->integer('league_id')->nullable();
            $table->integer('season_id')->nullable();
            $table->integer('team_id')->nullable();

            $table->integer('position')->nullable();
            $table->integer('games_played')->nullable();
            $table->integer('wins')->nullable();
            $table->integer('draws')->nullable();
            $table->integer('losses')->nullable();
            $table->integer('scored')->nullable();
            $table->integer('conceded')->nullable();
            $table->integer('gd')->nullable();
            $table->integer('points');

            $table->text('recent_results')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('standings');
    }
};
