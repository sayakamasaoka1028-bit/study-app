<?php

namespace App\Http\Controllers;

use App\Models\StudyLog;
use Illuminate\Http\Request;

class StudyLogController extends Controller
{
    public function create()
    {
        return view('study.create');
    }
    public function index()
    {
    $logs = StudyLog::orderBy('study_date', 'desc')->get();
    return view('study.list', compact('logs'));
    }


    public function store(Request $request)
    {
        StudyLog::create([
            'study_date' => now()->toDateString(),
            'count' => $request->count,
        ]);

        return redirect('/study');
    }
}
