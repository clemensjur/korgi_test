<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class EventController extends Controller
{
    function show()
    {
        $user = User::find(Auth::user()->id);

        $teams = $user->allTeams()->where("personal_team", 0);

        $groups = UtilController::formatGroupsEloquentCollection($user, $teams);

        return Inertia::render("Events/Events", [
            "user" => $user,
            "groups" => $groups,
        ]);
    }

    function store(Request $request)
    {
        $team = Team::find($request->groupId);
        $event = Event::create([
            "name" => $request->name,
            "date" => $request->date,
            "description" => $request->description,
            "team_id" => $team->id,
        ]);
        $event->team()->associate($team);

        return [
            "name" => $event->name,
            "date" => $event->date,
            "description" => $event->description,
            "team_id" => $team->id,
            "team_url" => $team->url,
            "team_name" => $team->name
        ];
    }

    function user_events()
    {
        $user = User::find(Auth::user()->id);
        $teams = $user->allTeams();
        $events = [];

        foreach ($teams as $team) {
            $event = [];
            foreach ($team->events as $eventModel) {
                array_push($event, [
                    "name" => $eventModel->name,
                    "date" => $eventModel->date,
                    "description" => $eventModel->description,
                    "team_id" => $team->id,
                    "team_name" => $team->name,
                    "team_url" => $team->url
                ]);
            }
            array_push($events, $event);
        }

        return $events;
    }

    function group_events($url)
    {
        $user = User::find(Auth::user()->id);
        $team = $user->allTeams()->where("url", $url)->first();
        return $team->events;
    }
}
