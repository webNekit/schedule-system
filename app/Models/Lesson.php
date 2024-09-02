<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    protected $fillable = [
        '1_subject_id', '1_teacher_id', '1_room_id', '1_is_empty',
        '2_subject_id', '2_teacher_id', '2_room_id', '2_is_empty',
        '3_subject_id', '3_teacher_id', '3_room_id', '3_is_empty',
        '4_subject_id', '4_teacher_id', '4_room_id', '4_is_empty',
        '5_subject_id', '5_teacher_id', '5_room_id', '5_is_empty',
        '6_subject_id', '6_teacher_id', '6_room_id', '6_is_empty',
        '7_subject_id', '7_teacher_id', '7_room_id', '7_is_empty',
    ];
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // Связи с таблицами предметов, преподавателей и кабинетов
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subjects', 'id', 'id')
            ->whereIn('id', [
                $this->{'1_subject_id'},
                $this->{'2_subject_id'},
                $this->{'3_subject_id'},
                $this->{'4_subject_id'},
                $this->{'5_subject_id'},
                $this->{'6_subject_id'},
                $this->{'7_subject_id'},
            ]);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teachers', 'id', 'id')
            ->whereIn('id', [
                $this->{'1_teacher_id'},
                $this->{'2_teacher_id'},
                $this->{'3_teacher_id'},
                $this->{'4_teacher_id'},
                $this->{'5_teacher_id'},
                $this->{'6_teacher_id'},
                $this->{'7_teacher_id'},
            ]);
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'rooms', 'id', 'id')
            ->whereIn('id', [
                $this->{'1_room_id'},
                $this->{'2_room_id'},
                $this->{'3_room_id'},
                $this->{'4_room_id'},
                $this->{'5_room_id'},
                $this->{'6_room_id'},
                $this->{'7_room_id'},
            ]);
    }
}
