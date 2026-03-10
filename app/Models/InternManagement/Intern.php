<?php

namespace App\Models\InternManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Intern extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'intern_code',
        'username',
        'password',
        'name',
        'email',
        'joining_date',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
