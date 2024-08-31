<?php

namespace App\Livewire;

use App\Models\Semester;
use Livewire\Component;

class SemesterList extends Component
{

    public $semesters;

    public function mount()
    {
        $this->semesters = Semester::all(); // Получение всех семестров из базы данных
    }

    public function delete($id)
    {
        $semester = Semester::find($id);
        $semester->delete();

        session()->flash('message', 'Семестр удален успешно.');
        $this->semesters = Semester::all(); // Обновление списка семестров
    }

    public function render()
    {
        return view('livewire.semester-list');
    }
}
