<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHammerPriceFranchiseToPlayersMst extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('players_mst', function (Blueprint $table) {
            $table->integer('hammer_price')->length(20)->after('year')->nullable();
            $table->integer('franchise_id')->length(20)->after('hammer_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('players_mst', function (Blueprint $table) {
            $table->dropColumn('hammer_price');
            $table->dropColumn('franchise');
        });
    }
}
