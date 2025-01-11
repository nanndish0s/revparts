<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Auth\User as MongoUser;
use App\Models\Cart; // Add Cart model import

class User extends MongoUser
{
    use Notifiable;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Add role to fillable attributes
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Default role if not specified
     */
    protected $attributes = [
        'role' => 'user', // Default role
    ];

    /**
     * Check if the user is an admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get the user's role
     *
     * @return string
     */
    public function getRole(): string
    {
        return $this->role ?? 'user';
    }

    /**
     * Get the user's cart
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
}
