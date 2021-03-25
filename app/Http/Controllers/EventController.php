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

        $groups = $this->formatGroupsEloquentCollection($user, $teams);

        return Inertia::render("Events/Events", [
            "user" => $user,
            "groups" => $groups,
        ]);
    }

    function store(Request $request)
    {
        $team = Team::find($request->groupId);
        Log::info($request);
        $event = Event::create([
            "name" => $request->name,
            "date" => $request->date, //Carbon::parse($request->date),
            "description" => $request->description,
            "team_id" => $team->id,
        ]);
        $event->team()->associate($team);

        Log::info($team);

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

    function urlFormat($name)
    {
        $name = strtolower($name);
        return str_replace(" ", "-", $name);
    }

    function formatGroupsEloquentCollection(User $user, $collection)
    {
        $groups = [];

        foreach ($collection as $team) {
            $chatsObject = Chat::where("team_id", $team->id)->get();
            $uuids = [];

            foreach ($chatsObject as $chat) {
                array_push($uuids, $chat->uuid);
            }

            $users = [];

            foreach ($team->allUsers() as $user) {
                array_push($users, [
                    "id" => $user->id,
                    "name" => $user->name,
                    "isAdmin" => $user->hasTeamRole($team, "admin") || $user->hasTeamRole($team, "owner")
                ]);
            }

            // Log::info(implode(", ", $uuids));
            // Log::info($chat->uuid);

            // Log::info($user->hasTeamRole($team, "admin"));

            array_push($groups, [
                $this->urlFormat($team->name) => [
                    "id" => $team->id,
                    "uuid" => $team->uuid,
                    "name" => $team->name,
                    "url" => $this->urlFormat($team->name),
                    "hasAdminPermissions" => $user->teamRole($team),
                    "events" => [],
                    "color" => DB::table("team_user")->where([
                        ["user_id", "=", $user->id],
                        ["team_id", "=", $team->id]
                    ])->pluck("color")->first(),
                    "channels" => [
                        "allgemein" => [
                            "name" => "Allgemein",
                            "url" => "allgemein",
                            "uuid" => $uuids[0],
                        ],
                        "wichtig" => [
                            "name" => "Wichtig",
                            "url" => "wichtig",
                            "uuid" => $uuids[1],
                        ]
                    ],
                    "users" =>  $users
                ]
            ]);
        }
        // Log::info($groups);
        return $groups;
    }

    function formatGroupTeam(User $user, Team $team)
    {
        $uuids = Chat::where("team_id", $team->id)->get(["uuid"]);

        $users = [];
        $admins = [];

        foreach ($team->allUsers() as $user) {
            array_push($users, [
                "id" => $user->id,
                "name" => $user->name,
                "isAdmin" => $user->hasTeamRole($team, "admin") || $user->hasTeamRole($team, "owner")
            ]);
        }

        // Log::info("User Permissions: ");
        // Log::info($user->teamRole($team));

        return [
            $team->name => [
                "id" => $team->id,
                "uuid" => $team->uuid,
                "name" => $team->name,
                "url" => $this->urlFormat($team->name),
                "admins" => $admins,
                "hasAdminPermissions" => $user->teamRole($team),
                "events" => [],
                "color" => DB::table("team_user")->where([
                    ["user_id", "=", $user->id],
                    ["team_id", "=", $team->id]
                ])->pluck("color")->first(),
                "channels" => [
                    "allgemein" => [
                        "name" => "Allgemein",
                        "url" => "allgemein",
                        "uuid" => $uuids[0],
                    ],
                    "wichtig" => [
                        "name" => "Wichtig",
                        "url" => "wichtig",
                        "uuid" => $uuids[1],
                    ]
                ],
                "users" =>  $users
            ]
        ];
    }

    function getChatsFromEloquentCollection(Collection $collection)
    {
        $chats = [];
        foreach ($collection as $team) {
            $chatsObject = Chat::where("team_id", $team->id)->get();

            foreach ($chatsObject as $chat) {
                array_push($chats, $chat);
            }
        }
        return $chats;
    }
}
