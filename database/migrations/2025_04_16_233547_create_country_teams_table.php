<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('country_teams', function (Blueprint $table) {
            $table->id(); // bigint unsigned
            $table->integer('team_id');
            $table->string('team_name')->nullable();
            $table->string('slug');
            $table->string('logo')->nullable()->default('images/club.png');
            $table->unsignedBigInteger('league_id')->nullable();
            $table->timestamps();

            $table->string('city')->nullable();
            $table->longText('info')->nullable();
            $table->string('stadium')->nullable();
            $table->string('date')->nullable();
            $table->string('address')->nullable();
            $table->string('website')->nullable();
            $table->string('stadium_capacity')->nullable();
            $table->timestamp('last_collect_time')->nullable();
            $table->integer('team_type')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_teams');
    }
};
