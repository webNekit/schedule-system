<?php

namespace App\Http\Controllers;

use App\Models\Workday;
use Illuminate\Http\Request;

class WorkdayController extends Controller
{
    public function index()
    {
        return view('workdays.index', [
            'pageTitle' => 'Рабочие дни',
            'buttonTitle' => 'Добавить рабочий день',
        ]);
    }

    public function create()
    {
        return view('workdays.create', [
            'pageTitle' => 'Добавить рабочий день',
            'buttonTitle' => 'Добавить рабочий день',
        ]);
    }

    public function edit(Workday $workday)
    {
        return view('workdays.edit', [
            'workday' => $workday,
            'pageTitle' => 'Редактировать рабочий день',
            'buttonTitle' => 'Редактироввать рабочий день',
        ]);
    }
}
