<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('departments.index', [
            'pageTitle' => 'Кафедры',
            'buttonTitle' => 'Добавить кафедру',
        ]);
    }

    public function create()
    {
        return view('departments.create', [
            'pageTitle' => 'Добавить кафедру',
            'buttonTitle' => 'Добавить кафедру',
        ]);
    }

    public function edit(Department $department)
    {
        return view('departments.edit', [
            'department' => $department,
            'pageTitle' => 'Редактировать кафедру',
            'buttonTitle' => 'Добавить кафедру',
        ]);
    }
}
