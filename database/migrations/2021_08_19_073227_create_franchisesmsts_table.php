<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFranchisesmstsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchisesmsts', function (Blueprint $table) {
            $table->increments('franchise_id')->length(10);
            $table->string('franchise_name', 100);
            $table->string('franchise_abbrivation', 100);
            $table->integer('year')->length(4);
            $table->integer('franchise_auction_year')->length(4);
            $table->integer('indian_players_acquired_before_auction')->length(100);
            $table->integer('pre_auction_budget')->length(10);
            $table->integer('overseas_players_acquired_before_the_auction')->length(100);
            $table->timestamp('franchise_created_on')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('franchise_modified_on')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->integer('franchise_created_by');
            $table->integer('franchise_modified_by');
            $table->string('rtm_before_auction', 255);
            $table->boolean('franchise_status')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('franchisesmsts');
    }
}