<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupSubject extends Model
{
    use HasFactory;
    protected $fillable = ['group_id', 'subject_id'];

    // Связь с группой
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    // Связь с дисциплиной
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
