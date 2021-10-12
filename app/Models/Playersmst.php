<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playersmst extends Model
{
    use HasFactory;

    protected $table = 'players_mst';

    protected $primaryKey = 'player_id';

    public $fillable = [
        'player_name',
        'player_nationality',
        'marquee_player',
        'bought_via_rtm',
        'player_speciality',
        'player_auction_status',
        'user_photo_url',
        'reserve_price',
        'year',
        'player_created_by',
        'player_modified_by'
    ];
}
