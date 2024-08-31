<?php

namespace App\Livewire;

use App\Models\Semester;
use Livewire\Component;

class SemesterForm extends Component
{
    public $semesterId;
    public $name;
    public $start_date;
    public $end_date;

    public function mount($id = null)
    {
        if ($id) {
            $semester = Semester::findOrFail($id);
            $this->semesterId = $semester->id;
            $this->name = $semester->name;
            $this->start_date = $semester->start_date;
            $this->end_date = $semester->end_date;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        Semester::updateOrCreate(
            ['id' => $this->semesterId],
            [
                'name' => $this->name,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]
        );

        session()->flash('message', $this->semesterId ? 'Семестр обновлен успешно.' : 'Семестр создан успешно.');

        return redirect()->route('semesters.index');
    }

    public function render()
    {
        return view('livewire.semester-form');
    }
}
