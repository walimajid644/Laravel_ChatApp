<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Workspace extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'workspaces';

    protected $fillable = [
        'name',
        'owner_id',
        'members'
    ];
}