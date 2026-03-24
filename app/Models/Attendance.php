<?php

namespace App\Models;

use App\Models\InternManagement\Intern;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = ['intern_id', 'date', 'status', 'note'];

    public function intern(): BelongsTo
    {
        return $this->belongsTo(Intern::class);
    }
}
