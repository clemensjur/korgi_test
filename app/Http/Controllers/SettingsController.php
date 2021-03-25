<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SettingsController extends Controller
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
}
