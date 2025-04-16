<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->integer('game_id')->index();
            $table->integer('event_type');
            $table->string('minute');
            $table->integer('team_id');
            $table->string('assist_player')->nullable();
            $table->string('player_name')->nullable();
            $table->integer('player_id')->nullable();
            $table->integer('assist_palyer_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
