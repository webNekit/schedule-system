<?php

namespace App\Livewire\Teachers;

use App\Models\Teacher;
use App\Models\Subject;
use Livewire\Component;
use Livewire\WithPagination;

class TeachersList extends Component
{
    use WithPagination;

    public function render()
    {
        // Получаем всех преподавателей и все дисциплины
        $teachers = Teacher::paginate(10);
        $allSubjects = Subject::all()->keyBy('id'); // Используем keyBy для быстрого доступа

        return view('livewire.teachers.teachers-list', [
            'teachers' => $teachers,
            'allSubjects' => $allSubjects,
        ]);
    }
}
