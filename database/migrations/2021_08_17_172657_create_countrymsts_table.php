<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountrymstsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countrymsts', function (Blueprint $table) {
            $table->increments('country_id')->length(10);
            $table->string('country_name', 255);
            $table->string('country_flag', 255);            
            $table->timestamp('created_on')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('modified_on')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->boolean('country_status')->default('1');
            $table->string('country_nationality', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countrymsts');
    }
}
