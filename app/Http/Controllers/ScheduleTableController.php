<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleTableController extends Controller
{
    public function index()
    {
        return view('shedulesTable.index', [
            'title' => 'Расписание на: ',
        ]);
    }
    public function view($workdayId, $departmentId)
    {
        // Получаем расписания для конкретного рабочего дня и кафедры
        $schedules = Schedule::where('workday_id', $workdayId)
            ->where('department_id', $departmentId)
            ->with(['group', 'lesson', 'department', 'workday'])
            ->get();

        // Получаем все уникальные группы для данной кафедры
        $groups = $schedules->pluck('group')->unique('id');

        // Получаем все lesson_ids, связанные с этими группами
        $lessons = Lesson::whereIn('id', $schedules->pluck('lesson_id')->unique())->get();

        $title = "Расписание для кафедры ID: $departmentId на " . optional($schedules->first()->workday)->date ?? 'Неизвестная дата';

        return view('shedulesTable.index', compact('schedules', 'title', 'groups', 'lessons'));
    }
}
