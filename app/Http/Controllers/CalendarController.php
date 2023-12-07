<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;


class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request)
    {
        $events = [];

        if (Auth::user()->isAdmin) {
            $tasks = Task::all();
        }
        else {
            //$tasks = auth()->user()->tasks();
            $tasks = Task::allForUser(auth()->user()->id);
            //dd($tasks);
        }
        
        foreach ($tasks as $task) {
            $events[] = [
                'title' => $task->name,
                'start' => $task->start_datetime,
                'end' => $task->end_datetime,
                'url' => route('maps', ['type' => 'gmaps', 'address' => $task->address]),
            ];
        }
        
        return view('calendar.calendar', compact('events'));
    }
}
