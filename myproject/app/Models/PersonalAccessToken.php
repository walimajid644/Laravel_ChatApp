<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;


class PersonalAccessToken extends Model 
{
    protected $connection = 'mongodb';
    protected $collection = 'personal_access_tokens';

    protected $fillable = [
        'name',
        'token',
        'abilities',
        'expires_at',
        'tokenable_id',
        'tokenable_type',
    ];

    protected $casts = [
        'abilities' => 'json',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    
    public function tokenable()
    {
        return $this->morphTo();
    }

    
    public static function findToken($token)
    {
        if (strpos($token, '|') === false) {
            return static::where('token', hash('sha256', $token))->first();
        }

        [$id, $token] = explode('|', $token, 2);

        
        if ($instance = static::find($id)) {
            
            if (hash_equals($instance->token, hash('sha256', $token))) {
                return $instance;
            }
        }

        return null;
    }

    public function can($ability)
    {
        return in_array('*', $this->abilities) ||
               array_key_exists($ability, array_flip($this->abilities));
    }

    public function cant($ability)
    {
        return ! $this->can($ability);
    }
}