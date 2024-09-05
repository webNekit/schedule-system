<?php

namespace App\Livewire\Workdays;

use App\Models\Workday;
use Livewire\Component;

class WorkdayForm extends Component
{
    public $workday;
    public $date;
    public $is_active = false; // Добавьте это свойство

    public function mount(Workday $workday = null)
    {
        $this->workday = $workday ?: new Workday();
        $this->date = $this->workday->date;
        $this->is_active = $this->workday->is_active; // Инициализируйте is_active из модели Workday
    }

    public function save()
    {
        // Валидация полей
        $this->validate([
            'date' => 'required',
            'is_active' => 'boolean', // Обновите валидацию
        ]);

        // Деактивация всех других записей, если текущая запись установлена как активная
        if ($this->is_active) {
            Workday::where('id', '!=', $this->workday->id)->update(['is_active' => false]);
        }

        // Сохранение данных текущей записи
        $this->workday->date = $this->date;
        $this->workday->is_active = $this->is_active;
        $this->workday->save();

        session()->flash('message', $this->workday->exists ? 'Рабочий день обновлен.' : 'Рабочий день создан');

        return redirect()->route('workdays.index');
    }

    public function render()
    {
        return view('livewire.workdays.workday-form');
    }
}
