<?php

namespace App\Models\InterviewManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Carbon\Carbon;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_code',
        'verification_token',
        'email',
        'email_verified_at',
        'name',
        'phone',
        'college',
        'degree',
        'year',
        'cgpa',
        'domain',
        'duration',
        'duration_unit',
        'skills',
        'resume_path',
        'status',
    ];

}
