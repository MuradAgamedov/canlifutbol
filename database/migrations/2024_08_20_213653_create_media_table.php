<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Add this column
            $table->string('file_name')->nullable(); // Add this column
            $table->string('disk')->nullable(); // Add this column
            $table->string('conversions_disk')->nullable(); // Add this column
            $table->string('collection_name')->nullable(); // Add this column
            $table->string('mime_type')->nullable(); // Add this column
            $table->integer('size')->nullable(); // Add this column
            $table->json('custom_properties')->nullable(); // Add this column
            $table->json('generated_conversions')->nullable(); // Add this column
            $table->json('responsive_images')->nullable(); // Add this column
            $table->json('manipulations')->nullable(); // Add this column
            $table->unsignedBigInteger('model_id')->nullable(); // This is already in your migration
            $table->string('model_type')->nullable(); // This is already in your migration
            $table->uuid('uuid')->nullable(); // Add this column
            $table->integer('order_column')->nullable(); // This is already in your migration
            $table->timestamps(); // This is already in your migration
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
