<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        return view('subject.index');
    }

    public function create()
    {
        return view('subject.create');
    }

    public function edit($id)
    {
        return view('subject.edit', ['id' => $id]);
    }
    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();
        
        return redirect()->route('subjects.index')->with('message', 'Дисциплина удалена успешно.');
    }
}
