<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; 

class Message extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'messages';

    protected $fillable = [
        'channel_id', 
        'user_id', 
        'content', 
        'attachment_path',
        'attachment_path', 
        'attachment_name'
    ];
}