<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Inertia\Inertia;#

class ChatController extends Controller
{
    function show($url)
    {
        $user = User::find(Auth::user()->id);
        $team = $user->allTeams()->where("url", $url)->first();

        if ($team == null) {
            echo "Error finding group";
        } else {
            $group = UtilController::formatGroupTeam($user, $team);
            return Inertia::render(
                "Group/Group",
                [
                    "group" => $group,
                    "chats" => UtilController::formatGroupTeam($user, $team)["channels"],
                    "user_is_admin" => $group["hasAdminPermissions"]
                ]
            );
        }
    }
}
