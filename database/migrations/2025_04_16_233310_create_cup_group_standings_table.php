<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cup_group_standings', function (Blueprint $table) {
            $table->id();
            $table->integer('league_id')->nullable();
            $table->integer('season_id')->nullable();
            $table->timestamps();

            $table->string('rank')->nullable();
            $table->string('group')->nullable();
            $table->string('team_id')->nullable(); // əgər lazım olsa int-a çevirərik
            $table->string('total')->nullable();
            $table->string('win')->nullable();
            $table->string('draw')->nullable();
            $table->string('loses')->nullable();
            $table->string('scored')->nullable();
            $table->string('conceded')->nullable();
            $table->string('diff')->nullable();
            $table->string('points')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cup_group_standings');
    }
};
