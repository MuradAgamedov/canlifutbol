<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leagues', function (Blueprint $table) {
            $table->smallIncrements('id'); // smallint unsigned AUTO_INCREMENT
            $table->integer('league_id')->index();
            $table->string('league_name')->nullable()->index();
            $table->string('league_short_name')->nullable()->index();
            $table->string('slug')->nullable()->index();
            $table->longText('text')->nullable();
            $table->string('logo')->nullable()->default('images/default_league.png');
            $table->integer('type')->nullable();
            $table->integer('pos')->index();
            $table->unsignedBigInteger('country_id')->nullable()->index();
            $table->timestamps();
            $table->timestamp('last_collect_time')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leagues');
    }
};
