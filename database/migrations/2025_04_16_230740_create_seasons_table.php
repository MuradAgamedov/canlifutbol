<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->integer('league_id')->nullable()->index();
            $table->string('title')->nullable();
            $table->dateTime('created_at');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->string('standing_gets_at')->nullable();
            $table->string('league_sub_id')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};
