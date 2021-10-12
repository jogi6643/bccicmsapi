<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersMstTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players_mst', function (Blueprint $table) {
            $table->increments('player_id')->length(10);
            $table->string('player_name', 50);
            $table->string('player_nationality', 50);
            $table->boolean('marquee_player')->default('0');
            $table->boolean('bought_via_rtm')->default('0');
            $table->enum('player_speciality', ['Batsman', 'Bowler', 'Wicket Keeper', 'All-Rounder']);
            $table->enum('player_auction_status', ['To Be Auctioned', 'Sold', 'Unsold'])->default('To Be Auctioned');
            $table->string('user_photo_url', 255);
            $table->integer('reserve_price')->length(20);
            $table->integer('year')->length(5);
            $table->timestamp('player_created_on')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('player_modified_on')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->integer('player_created_by')->length(10);
            $table->integer('player_modified_by')->length(10); 
            $table->boolean('player_status')->default('1');
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
        Schema::dropIfExists('players_mst');
    }
}
