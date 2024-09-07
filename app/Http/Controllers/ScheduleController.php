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

    public function transfer(Request $request)
    {
        $request->validate([
            'source_workday_id' => 'required|exists:workdays,id',
            'target_workday_id' => 'required|exists:workdays,id',
        ]);

        $sourceWorkdayId = $request->input('source_workday_id');
        $targetWorkdayId = $request->input('target_workday_id');

        // Получаем все расписания для исходного рабочего дня
        $schedules = Schedule::where('workday_id', $sourceWorkdayId)->get();

        foreach ($schedules as $schedule) {
            // Создаем новое расписание для целевого рабочего дня
            Schedule::create([
                'workday_id' => $targetWorkdayId,
                'department_id' => $schedule->department_id,
                'group_id' => $schedule->group_id,
                'lesson_id' => $schedule->lesson_id,
                'is_active' => $schedule->is_active,
                'is_archive' => $schedule->is_archive,
            ]);
        }

        return redirect()->route('schedules.index')->with('message', 'Расписание успешно перенесено.');
    }
}
