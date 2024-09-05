<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workday extends Model
{
    use HasFactory;
    protected $fillable = ['date', 'is_active'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
