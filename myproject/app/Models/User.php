<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable; 

    protected $connection = 'mongodb';
    
    protected $fillable = ['name', 'email', 'password', 'otp','api_token'];

}