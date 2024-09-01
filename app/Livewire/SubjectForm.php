<?php

namespace App\Livewire;

use App\Models\Subject;
use Livewire\Component;

class SubjectForm extends Component
{
    public $subjectId;
    public $name;
    public $total_hours;
    public $semester_id;

    public function mount($id = null)
    {
        if ($id) {
            $subject = Subject::findOrFail($id);
            $this->subjectId = $subject->id;
            $this->name = $subject->name;
            $this->total_hours = $subject->total_hours;
            $this->semester_id = $subject->semester_id;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'total_hours' => 'required|integer|min:1',
            'semester_id' => 'required|exists:semesters,id',
        ]);

        Subject::updateOrCreate(
            ['id' => $this->subjectId],
            [
                'name' => $this->name,
                'total_hours' => $this->total_hours,
                'semester_id' => $this->semester_id,
            ]
        );

        session()->flash('message', $this->subjectId ? 'Дисциплина обновлена успешно.' : 'Дисциплина создана успешно.');

        return redirect()->route('subjects.index');
    }

    public function render()
    {
        return view('livewire.subject-form');
    }
}
