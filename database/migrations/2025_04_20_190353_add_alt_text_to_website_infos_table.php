<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('website_infos', function (Blueprint $table) {
            $table->string('logo_header_alt_text')->nullable();
            $table->string('logo_footer_alt_text')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('website_infos', function (Blueprint $table) {
            $table->dropColumn(['logo_header_alt_text', 'logo_footer_alt_text']);
        });
    }
};
