<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class EventController extends Controller
{
    function show()
    {
        Log::info($this->user_events());
        return Inertia::render("Events/Events", [
            "user" => User::find(Auth::user()->id),
            "groups" => User::find(Auth::user()->id)->allTeams(),
        ]);
    }

    function store(Request $request)
    {
        $team = Team::find($request->groupId);
        Log::info($request);
        $event = Event::create([
            "name" => $request->name,
            "date" => Carbon::parse($request->date),
            "description" => $request->description,
            "team_id" => $team->id,
            "team_url" => $this->urlFormat($team->name),
            "team_name" => $team->name,
        ]);
        $event->team()->associate($team);

        return $event;
    }

    function user_events()
    {

        $user = User::find(Auth::user()->id);
        $teams = $user->allTeams();
        $events = [];

        foreach ($teams as $team) {
            array_push($events, $team->events);
        }

        return $events;
    }

    function urlFormat($name)
    {
        $name = strtolower($name);
        return str_replace(" ", "-", $name);
    }

    function group_events($url)
    {
        $user = User::find(Auth::user()->id);
        $team = $user->allTeams()->where("url", $url)->first();
        return $team->events;
    }
}
