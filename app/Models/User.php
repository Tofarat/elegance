<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens; // Import the HasApiTokens trait
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;





class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'phone', 'role'];

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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    //where my codes start from

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function shippingAddresses()
    {
        return $this->hasMany(ShippingAddress::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
};
