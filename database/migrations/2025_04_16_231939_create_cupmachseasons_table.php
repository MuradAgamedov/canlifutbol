<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cupmachseasons', function (Blueprint $table) {
            $table->id(); // int AUTO_INCREMENT
            $table->integer('league_id')->nullable();
            $table->string('title')->nullable();
            $table->dateTime('created_at');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->string('standing_gets_at')->nullable();
            $table->string('league_sub_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cupmachseasons');
    }
};
