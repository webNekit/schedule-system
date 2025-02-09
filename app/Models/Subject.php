<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'total_hours', 'semester_id'];

    // Связь с семестром
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    // Связь с группами
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_subjects');
    }

    // Связь с расписанием
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'subject_teacher');
    }
}
