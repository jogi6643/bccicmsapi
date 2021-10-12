<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userroles extends Model
{
    use HasFactory;
    public $fillable = [
        'role_name',
        'role_status',
        'read',
        'write',
        'country_nationality',
    ];
    public $timestamps = false;
}
