<?php

namespace App\Livewire;

use App\Models\Semester;
use App\Models\Subject;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class SubjectsList extends Component
{
    use WithPagination;

    #[Url()]
    public $semester = 'all';

    public function setSemester($semesterId)
    {
        $this->semester = $semesterId;
        $this->resetPage(); // Сбрасываем пагинацию при изменении семестра
    }

    #[Computed()]
    public function subjects()
    {
        $query = Subject::query();

        if ($this->semester != 'all') {
            $query->where('semester_id', $this->semester);
        }

        // Применяем пагинацию после всех условий
        return $query->paginate(10);
    }
    
    #[Computed()]
    public function semesters()
    {
        return Semester::all();
    }

    public function delete($id)
    {
        $subject = Subject::find($id);

        if ($subject) {
            $subject->delete();
            session()->flash('message', 'Дисциплина удалена успешно.');
        }

        // Сбрасываем пагинацию после удаления и обновляем данные
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.subjects-list', [
            'subjects' => $this->subjects,
            'semesters' => $this->semesters,
        ]);
    }
}