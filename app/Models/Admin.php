<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    // Add your fillable properties and other model configurations
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $table = 'admins'; // Specify the database table if not 'admins'

    protected $hidden = [
        'password', 'remember_token',
    ];
}
