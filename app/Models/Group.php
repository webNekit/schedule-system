<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'department_id'];

    // Связь с кафедрой
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Связь с дисциплинами
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'group_subjects');
    }

    // Связь с расписанием
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
