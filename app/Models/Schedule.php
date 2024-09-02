<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'workday_id', 'department_id', 'group_id', 'lesson_id', 'is_active', 'is_archive'
    ];

    public function workday()
    {
        return $this->belongsTo(Workday::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
