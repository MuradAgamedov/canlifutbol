<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('country_id')->index();
            $table->string('name')->index();
            $table->string('flag')->nullable();
            $table->string('slug')->index();
            $table->tinyInteger('status')->nullable()->default(1);
            $table->integer('area_id')->nullable()->index();
            $table->integer('pos')->nullable()->index();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
