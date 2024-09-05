<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Workday;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('schedules.index', [
            'pageTitle' => 'Расписание',
            'buttonTitle' => 'Расписание на новый день',
        ]);
    }

    public function create()
    {
        return view('schedules.create', [
            'pageTitle' => 'Создать расписание',
            'buttonTitle' => 'Создать расписание',
            'departments' => Department::all(),
            'groups' => Group::all(),
            'subjects' => Subject::all(),
            'teachers' => Teacher::all(),
            'rooms' => Room::all(),
            'workdays' => Workday::all(),
            'schedule' => null, // Указываем, что объект расписания отсутствует
            'selectedDepartment' => null,
            'selectedGroup' => null,
            'selectedLesson' => null,
        ]);
    }


    public function edit(Schedule $schedule, $departmentId, $groupId, $lessonId)
{
    // Получение данных для редактирования
    $departments = Department::all();
    $groups = Group::all();
    $subjects = Subject::all();
    $teachers = Teacher::all();
    $rooms = Room::all();
    $workdays = Workday::all();

    // Найдите выбранные объекты по ID
    $selectedDepartment = Department::find($departmentId);
    $selectedGroup = Group::find($groupId);
    $selectedLesson = Lesson::find($lessonId);

    // Передайте данные в представление
    return view('schedules.edit', [
        'schedule' => $schedule,
        'departments' => $departments,
        'groups' => $groups,
        'subjects' => $subjects,
        'teachers' => $teachers,
        'rooms' => $rooms,
        'workdays' => $workdays,
        'selectedDepartment' => $selectedDepartment,
        'selectedGroup' => $selectedGroup,
        'selectedLesson' => $selectedLesson,
        'pageTitle' => 'Редактировать расписание',
        'buttonTitle' => 'Сохранить изменения',
    ]);
}

}
