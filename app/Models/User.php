<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    public function cart(){
        return $this->hasMany(Cart::class);
    }

    protected $fillable = [
    'name',
    'email',
    'password',
    'gender',
    'date_of_birth',
    'role',
    'membership_expiry'   // <-- add this
];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'membership_expiry' => 'datetime',  // add this
    ];
    
    public function transactions()
{
    return $this->hasMany(Transaction::class, 'transaction_user_id');
}

public function carts()
{
    return $this->hasMany(Cart::class, 'user_id');
}

}
