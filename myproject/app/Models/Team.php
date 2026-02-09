<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Team extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'teams';

    protected $fillable = [
        'name',
        'workspace_id', 
        'description',
        'members' 
    ];
}