<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; 

class Channel extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'channels';

    protected $fillable = [
        'name', 
        'workspace_id', 
        'type', 
        'members' 
    ];
}