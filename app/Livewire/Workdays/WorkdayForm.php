<?php

namespace App\Livewire\Workdays;

use App\Models\Workday;
use Livewire\Component;

class WorkdayForm extends Component
{

    public $workday;
    public $date;

    public function mount(Workday $workday = null)
    {
        // Если $room передан, инициализируем его, иначе создаем новый
        $this->workday = $workday ?: new Workday();
        $this->date = $this->workday->date;
    }

    public function save()
    {
        // Валидация поля name
        $this->validate([
            'date' => 'required',
        ]);

        // Сохранение модели
        $this->workday->date = $this->date;
        $this->workday->save();

        // Уведомление об успешном сохранении
        session()->flash('message', $this->workday->exists ? 'Рабочий день обновлен.' : 'Рабочий день создан');
        
        // Перенаправление на список комнат
        return redirect()->route('workdays.index');
    }

    public function render()
    {
        return view('livewire.workdays.workday-form');
    }
}
