<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'department_id', 'weekly_hours', 'subjects_id'];

    // Связь с кафедрой
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

     public function subjects()
    {
        return $this->hasMany(Subject::class, 'id', 'subjects_id');
    }
    

    // В модели Group
public function getSubjectsAttribute()
{
    $subjectIds = json_decode($this->subjects_id, true); // Преобразуем JSON в массив

    if (is_array($subjectIds)) {
        return Subject::whereIn('id', $subjectIds)->get(); // Получаем дисциплины по массиву ID
    }

    return collect(); // Возвращаем пустую коллекцию, если массив пустой или не является массивом
}


    // Связь с расписанием
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
