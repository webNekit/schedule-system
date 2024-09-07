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

    public function room($pairNumber)
    {
        return $this->belongsTo(Room::class, "{$pairNumber}_room_id");
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function __call($method, $parameters)
    {
        // Проверка, что метод начинается с "subject", "teacher" или "room"
        if (preg_match('/^(subject|teacher|room)(\d+)$/', $method, $matches)) {
            $type = $matches[1];
            $index = $matches[2];
            
            // Создание связей для предметов, преподавателей и кабинетов
            if (in_array($type, ['subject', 'teacher', 'room']) && is_numeric($index)) {
                $idColumn = "{$index}_{$type}_id";
                
                // Определяем правильный класс для связи
                $relatedClass = ucfirst($type);
                $relatedModelClass = "App\\Models\\{$relatedClass}";

                return $this->belongsTo($relatedModelClass, $idColumn);
            }
        }
        
        return parent::__call($method, $parameters);
    }
}
