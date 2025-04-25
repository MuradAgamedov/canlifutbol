<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsiteInfosTable extends Migration
{
    public function up()
    {
        Schema::create('website_infos', function (Blueprint $table) {
            $table->id();

            $table->string('logo_header')->nullable();
            $table->string('logo_footer')->nullable();
            $table->text('copyright_text')->nullable();

            $table->string('facebook_link')->nullable()->index();
            $table->string('instagram_link')->nullable()->index();
            $table->string('twitter_link')->nullable()->index();
            $table->string('whatsapp_number')->nullable()->index();
            $table->string('number_1')->nullable()->index();
            $table->string('number_2')->nullable()->index();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('website_infos');
    }
}
