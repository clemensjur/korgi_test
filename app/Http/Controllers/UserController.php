<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Laravel\Jetstream\Events\TeamMemberAdded;
use Laravel\Jetstream\Events\TeamMemberRemoved;

class UserController extends Controller
{
    function store(Request $request)
    {
        Log::info("Adding user to team");

        $user = User::find($request->userId);
        $team = Team::where("uuid", $request->uuid)->first();

        $team->users()->attach(
            $user,
            [
                'role' => 'editor',
                'color' => '#FFC78E'
            ]
        );

        TeamMemberAdded::dispatch($team, $user);
    }

    function remove(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $team = Team::where("uuid", $request->groupUuid)->first();
        $userToRemove = User::find($request->userId);

        if ($user->hasTeamRole($team, "admin") && !$userToRemove->ownsTeam($team)) {
            $team->removeUser($userToRemove);
            TeamMemberRemoved::dispatch($team, $userToRemove);
        } else {
            abort(403, "You don't have permission to perform this action!");
        }
    }

    function delete(Request $request)
    {
        $user = Auth::user();

        // Log::info($user);

        $teams = $user->allTeams();

        foreach ($teams as $team) {
            $team->chats()->delete();
            $team->delete();
        }

        User::find($user->id)->delete();
        DB::table("team_user")->where("user_id", $user->id)->delete();

        Auth::logout();

        return Redirect::route("home");
    }
}
