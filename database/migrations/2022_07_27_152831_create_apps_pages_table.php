<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppsPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apps_pages', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->date('date')->nullable();
            $table->text('link_web')->nullable();
            $table->text('link_android')->nullable();
            $table->text('link_ios')->nullable();
            $table->text('link_huawei')->nullable();
            $table->foreignId('category_id')->index();
            $table->boolean('status')->default(0);
            $table->string('company')->comment('digisol,kadytech');
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
        Schema::dropIfExists('apps_pages');
    }
}
