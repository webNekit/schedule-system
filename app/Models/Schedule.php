<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = ['subject_id', 'teacher_id', 'room_id', 'group_id', 'semester_id'];

    // Связь с дисциплиной
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Связь с преподавателем
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // Связь с кабинетом
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Связь с группой
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    // Связь с семестром
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    // Связь с расписанием слотов
    public function slots()
    {
        return $this->hasMany(ScheduleSlot::class);
    }
}
