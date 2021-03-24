<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    function store(Request $request)
    {
        $team = Team::find($request->groupId);
        Log::info($request);
        $event = Event::create([
            "name" => $request->name,
            "date" => Carbon::parse($request->date),
            "team_id" => $team->id
        ]);
        $event->team()->associate($team);

        return $event;
    }

    function user_events() {

    }

    function group_events($url) {

    }
}
