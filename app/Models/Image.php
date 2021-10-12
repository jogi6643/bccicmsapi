<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;


// use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';

    protected $collection = 'image';

    public $fillable = [
        'title',
        'description',
        'language',
        'type',
        'tags',
        'imageUrl',
        'metadata',
        'platform',
        'coordinates',
        'references',
        'date',
       'accountId'
    ];
}
