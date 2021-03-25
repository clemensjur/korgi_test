<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Inertia\Inertia;

class GroupController extends Controller
{

    function index()
    {
        $user = User::find(Auth::user()->id);

        $teams = $user->allTeams()->where("personal_team", 0);

        $groups = UtilController::formatGroupsEloquentCollection($user, $teams);

        return Inertia::render("Group/GroupView", [
            "user" => $user,
            "groups" => $groups,
        ]);
    }


    function store(Request $request)
    {
        $user = Auth::user();
        $team = Team::create([
            "name" => $request->input("name"),
            "personal_team" => false,
            "user_id" => $user->id,
            "url" => UtilController::urlFormat($request->input("name")),
            "uuid" => DB::raw('UUID()')
        ]);

        $team->users()->attach(
            $user->id,
            [
                "role" => "admin",
                "color" => $request->color
            ]
        );

        $general_chat = Chat::create([
            "team_id" => $team->id,
            "type" => false,
            "url" => "allgemein",
            "uuid" => DB::raw('UUID()')
        ]);

        $important_chat = Chat::create([
            "team_id" => $team->id,
            "type" => true,
            "url" => "wichtig",
            "uuid" => DB::raw('UUID()')
        ]);

        $general_chat->team()->associate($team);
        $important_chat->team()->associate($team);

        return UtilController::formatGroupTeam(User::find($user->id), $team);
    }

    function show($url)
    {
        $user = User::find(Auth::user()->id);
        $team = $user->allTeams()->where("url", $url)->first();

        $teams = $user->allTeams()->where("personal_team", 0);
        $groups = UtilController::formatGroupsEloquentCollection($user, $teams);

        if ($team == null) {
            echo "Error finding group";
        } else {
            $group = UtilController::formatGroupTeam($user, $team);

            return Inertia::render(
                "Group/Group",
                [
                    "group" => $group[$team->name],
                    "groups" => $groups,
                    "user" => $user,
                ]
            );
        }
    }

    function join($uuid)
    {
        $user = Auth::user();
        $group = Team::where("uuid", $uuid)->first();

        return Inertia::render("Group/JoinGroup", [
            "user" => $user,
            "group" => $group
        ]);
    }

    function update(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $team = Team::find($request->groupId);

        if ($user->hasTeamRole($team, "admin")) {
            $team->update(["name" => $request->groupName, "url" => UtilController::urlFormat($request->groupName)]);
        } else abort(500, "Insufficient Permissions");
    }

    function leave(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $team = Team::where("uuid", $request->uuid)->first();
        if ($user->ownsTeam($team)) {
            if ($team->users->contains("role", "admin")) {
                DB::table("team_user")->where([
                    ["team_id", "=", $team->id],
                    ["user_id", "=", $user->id]
                ])->delete();
            } else {
                abort(500, "Can't leave when you're the only admin! To leave make someone else an Administrator");
            }
        } else {
            DB::table("team_user")->where([
                ["team_id", "=", $team->id],
                ["user_id", "=", $user->id]
            ])->delete();
        }
    }

    function delete(Request $request)
    {
        $team = Team::where("uuid", $request->uuid)->first();
        DB::table("team_user")->where("team_id", $team->id)->delete();
        foreach ($team->chats as $chat) {
            $chat->delete();
        }
        $team->delete();
    }

    function users(Request $request, $url)
    {
        $user = User::find(Auth::user()->id);
        $team = $user->allTeams()->where("url", $url)->first();

        return Inertia::render("Group/Users", [
            "group" => $team,
            "users" => $team->allUsers(),
            "isAdmin" => $user->hasTeamRole($team, "admin")
        ]);
    }

    function set(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            if ($key != "groupId") {
                Team::where("id", $request->groupId)->update([$key => $value]);
            }
        }
    }

    function get(Request $request)
    {
        return Team::where("name", $request->groupName)->first();
    }
}
