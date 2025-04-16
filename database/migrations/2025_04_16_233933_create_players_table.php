<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->increments('id');
            $table->string('recordId')->nullable();
            $table->string('playerId')->nullable()->index();
            $table->string('name')->nullable()->index();
            $table->string('birthday')->nullable();
            $table->integer('height')->nullable();
            $table->string('country')->nullable();
            $table->string('feet')->nullable();
            $table->integer('team_type')->nullable();
            $table->integer('weight')->nullable();
            $table->string('photo')->nullable();
            $table->string('image', 191)->nullable();
            $table->string('value')->nullable();
            $table->string('teamId')->nullable()->index();
            $table->string('position')->nullable();
            $table->integer('number')->nullable();
            $table->string('introduce')->nullable()->index();
            $table->string('contractEndDate')->nullable();
            $table->integer('update_time')->nullable();
            $table->boolean('updated')->default(0);
            $table->timestamps();
            $table->timestamp('last_image_collect_time')->nullable();
            $table->string('slug')->nullable()->index();
            $table->integer('country_team_id')->nullable();
            $table->integer('country_team_number')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
