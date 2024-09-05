<?php

namespace App\Livewire\Schedules;

use App\Models\Schedule;
use Livewire\Component;

class SchedulesList extends Component
{
    public $schedules;

    protected $listeners = ['refreshSchedules' => 'loadSchedules'];

    public function mount()
    {
        $this->loadSchedules();
    }

    public function loadSchedules()
    {
        $this->schedules = Schedule::with(['group', 'department', 'workday', 'lesson'])->get();
    }

    public function render()
    {
        return view('livewire.schedules.schedules-list', ['schedules' => $this->schedules]);
    }
}
