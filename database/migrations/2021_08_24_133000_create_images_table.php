<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->longText('description')->nullable();
            $table->string('language', 100)->nullable();
            $table->enum('type', ['image', 'video', 'text'])->default('image')->nullable();
            $table->string('tags', 255)->nullable();
            $table->text('imageUrl');
            $table->text('metadata')->nullable();
            $table->text('platform')->nullable();
            $table->string('coordinates',255)->nullable();
            $table->string('related',255)->nullable();
            $table->string('references',255)->nullable();
            $table->date('date')->nullable();
            $table->integer('accountId')->nullable();
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
        Schema::dropIfExists('images');
    }
}
