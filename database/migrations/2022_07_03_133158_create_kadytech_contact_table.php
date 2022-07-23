<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKadyTechContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kadytech_contact', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('company_name')->nullable();
            $table->string('email');
            $table->string('phone_number');
            $table->string('WebSite_URL')->nullable();
            $table->string('social_media_account')->nullable();
            $table->boolean('web');
            $table->boolean('mobile');
            $table->boolean('market');
            $table->boolean('other');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kadytech_contact');
    }
}
