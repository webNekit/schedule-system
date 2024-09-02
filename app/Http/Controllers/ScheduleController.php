<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(){
        return view('schedules.index', [
            'pageTitle' => 'Расписание',
            'buttonTitle' => 'Добавить расписание',
        ]);
    }

    public function create()
    {
        return view('schedules.create', [
            'pageTitle' => 'Создать расписание',
            'buttonTitle' => 'Создать расписание',
        ]);
    }

    public function edit(Schedule $schedule)
    {
        return view('schedules.edit', [
            'schedule' => $schedule,
            'pageTitle' => 'Редактировать расписание',
            'buttonTitle' => 'Добавить расписание',
        ]);
    }
}
