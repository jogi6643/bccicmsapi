<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Countrymst extends Model
{
    use HasFactory;
    public $fillable = [
        'country_name',
        'country_flag',
        'country_status',
        'country_nationality',
    ];
}
