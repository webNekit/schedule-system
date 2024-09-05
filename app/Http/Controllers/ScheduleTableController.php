<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScheduleTableController extends Controller
{
    public function index()
    {
        return view('shedulesTable.index', [
            'title' => 'Расписание на 12.09.2024 | Финансы',
        ]);
    }
}
