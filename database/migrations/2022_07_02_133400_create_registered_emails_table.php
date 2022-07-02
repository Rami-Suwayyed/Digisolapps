<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisteredEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registered_emails', function (Blueprint $table) {
            $table->id();
            $table->string('media_type');
            $table->string('type_id');
            $table->string('full_name');
            $table->string('username');
            $table->text('Password');
            $table->string('email');
            $table->text('url');
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('registered_emails');
    }
}
