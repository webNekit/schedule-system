<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'start_date', 'end_date']; // Разрешенные для массового заполнения поля

    // Связь с дисциплинами
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
