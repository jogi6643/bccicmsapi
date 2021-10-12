<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class VideoContent extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    // comment after testing 
    // protected $collection = 'video';
    protected $collection = 'videos';

    public $fillable = ['title', 'content', 'tags'];

    protected $mappingProperties = array(
        'title' => [
          'type' => 'string',
          "analyzer" => "standard",
        ],
        'content' => [
          'type' => 'string',
          "analyzer" => "standard",
        ],
        'tags' => [
          'type' => 'string',
          "analyzer" => "stop",
          "stopwords" => [","]
        ],
      );
}