<?php

namespace App\Models\InternManagement;

use Illuminate\Database\Eloquent\Model;

class ManualCertificate extends Model
{
    //
    protected $fillable = [
        'intern_name', 'intern_code', 'internship_role', 'project_name',
        'project_description', 'joining_date', 'completion_date',
        'issuing_date', 'certificate_template', 'cert_token'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->cert_token)) {
                $model->cert_token = Str::random(64);
            }
        });
    }
}
