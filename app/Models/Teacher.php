<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'subjects_id'];

    protected $casts = [
        'subjects_id' => 'array',
    ];

    // Связь с расписанием
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }
}
