<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usersmst extends Model
{
    use HasFactory;
    public $fillable = [
        'user_id',
        'user_first_name',
        'user_last_name',
        'user_password',
        'user_title',
        'user_email_id',        
        'user_dob',
        'user_is_association',
        'user_group_id',
        'user_country_id',
        'user_address',
        'user_status',
        'user_created_by',
        'user_modified_by',
        'user_role_id',
        'user_is_online',
        'user_season_id',
        'association_id',
        'user_phone_number',
        'device_id',
        'flag_id',
        'user_otp',
        'user_gender',
        'user_photo_url',
    ];
    public $timestamps = false;
}
