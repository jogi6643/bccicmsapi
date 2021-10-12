<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersmstsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usersmsts', function (Blueprint $table) {
            $table->increments('user_id')->length(10);
            $table->string('user_first_name', 100);
            $table->string('user_last_name', 100);
            $table->string('user_password', 255);
            $table->enum('user_title', ['Mr.', 'Mrs.', 'Miss', 'Dr.'])->default('Mr.');
            $table->string('user_email_id', 100)->unique();
            $table->date('user_dob');
            $table->boolean('user_is_association')->default('1');
            $table->integer('user_group_id')->length(10);
            $table->integer('user_country_id');
            $table->string('user_address', 500);
            $table->boolean('user_status')->default('1');
            $table->timestamp('user_created_on')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('user_modified_on')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->integer('user_created_by');
            $table->integer('user_modified_by'); 
            $table->integer('user_role_id');            
            $table->boolean('user_is_online')->default('1');
            $table->string('user_season_id', 255);
            $table->integer('association_id')->length(10);
            $table->string('user_phone_number', 10);
            $table->string('device_id', 255);
            $table->integer('flag_id')->length(3);
            $table->string('user_otp', 10);
            $table->boolean('user_gender');
            $table->string('user_photo_url', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usersmsts');
    }
}
