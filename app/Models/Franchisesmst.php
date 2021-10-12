<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Franchisesmst extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = 'franchisesmsts';

    public $fillable = [
        'franchise_name',
        'franchise_abbrivation',
        'year',
        'franchise_auction_year',
        'indian_players_acquired_before_auction',
        'pre_auction_budget',
        'overseas_players_acquired_before_the_auction',
        'franchise_modified_on',
        'franchise_created_by',
        'franchise_modified_by',
        'rtm_before_auction',
    ];
}
