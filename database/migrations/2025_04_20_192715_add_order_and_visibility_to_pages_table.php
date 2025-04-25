<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderAndVisibilityToPagesTable extends Migration
{
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->integer('order')->nullable()->after('slug');
            $table->boolean('show_on_header')->default(false)->after('order');
            $table->boolean('show_on_footer')->default(false)->after('show_on_header');
        });
    }

    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['order', 'show_on_header', 'show_on_footer']);
        });
    }
}
