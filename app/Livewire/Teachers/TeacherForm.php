<?php

namespace App\Livewire\Teachers;

use App\Models\Subject;
use App\Models\Teacher;
use Livewire\Component;

class TeacherForm extends Component
{
    public $name; // Обычное поле для имени
    public $allSubjects;
    public $addedSubjects = []; // Список добавленных дисциплин
    public $teacherId; // ID преподавателя для редактирования

    public function mount($teacherId = null)
    {
        $this->teacherId = $teacherId;

        if ($teacherId) {
            // Загрузка существующего преподавателя по ID
            $teacher = Teacher::find($teacherId);
            if ($teacher) {
                $this->name = $teacher->name;
                $this->addedSubjects = $teacher->subjects_id ?? [];
            }
        }
        $this->allSubjects = Subject::all();
    }

    public function addSubject($subjectId)
    {
        if (!in_array($subjectId, $this->addedSubjects)) {
            $this->addedSubjects[] = $subjectId;
        }
    }

    public function removeSubject($subjectId)
    {
        $this->addedSubjects = array_filter($this->addedSubjects, function($id) use ($subjectId) {
            return $id != $subjectId;
        });
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        if ($this->teacherId) {
            // Обновление существующего преподавателя
            $teacher = Teacher::find($this->teacherId);
            if ($teacher) {
                $teacher->name = $this->name;
                $teacher->subjects_id = $this->addedSubjects;
                $teacher->save();
            }
        } else {
            // Создание нового преподавателя
            Teacher::create([
                'name' => $this->name,
                'subjects_id' => $this->addedSubjects
            ]);
        }

        session()->flash('message', 'Преподаватель успешно сохранен.');
        return redirect()->route('teachers.index');
    }

    public function render()
    {
        return view('livewire.teachers.teacher-form', [
            'allSubjects' => $this->allSubjects
        ]);
    }
}
