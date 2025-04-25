<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('player_techs', function (Blueprint $table) {
            $table->id();
            $table->integer('league_id')->nullable();
            $table->integer('season_id')->nullable();
            $table->integer('player_id')->nullable();

            $table->integer('play_count')->nullable();
            $table->integer('play_sub')->nullable();
            $table->string('mins')->nullable();
            $table->integer('standart_goals')->nullable();
            $table->integer('penalty_goals')->nullable();
            $table->integer('shots')->nullable();
            $table->integer('shog')->nullable();
            $table->string('player_name')->nullable();
            $table->integer('fauls')->nullable();
            $table->integer('best')->nullable();
            $table->string('raiting')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_techs');
    }
};
