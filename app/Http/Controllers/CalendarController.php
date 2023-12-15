<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Spatie\IcalendarGenerator\Components\Event;
use Spatie\IcalendarGenerator\Components\Calendar;

class CalendarController extends Controller
{
    /**
     * The __invoke method is responsible for fetching tasks based on the user's role (admin or regular user),
     *  processing them,
     *  and preparing the data for rendering the calendar view.
     */
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

    /**
     * this function downloads an ics file 
     */
    public function download()
    {   
        $user = User::findorfail(request('user'));

        if ($user->isAdmin) {
            $tasks = Task::all();
        }
        else {
            //$tasks = auth()->user()->tasks();
            $tasks = Task::allForUser($user->id);
            //dd($tasks);
        }

        $calendar = Calendar::create();

        foreach ($tasks as $task) {
            $event = Event::create()
                ->name($task->name) 
                ->description($task->description)
                ->address($task->address)
                ->startsAt(Carbon::parse($task->start_datetime))
                ->endsAt(Carbon::parse($task->end_datetime));
            $calendar->event($event);
        }

        $content = $calendar->get();

        return Response::make($content)
            ->header('Content-Type', 'text/calendar')
            ->header('Content-Disposition', 'attachment; filename="' . $user->name . '_event.ics"');
    }
}
