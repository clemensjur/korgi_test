<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class UtilController extends Controller
{
    public static function urlFormat($name)
    {
        $name = strtolower($name);
        return str_replace(" ", "-", $name);
    }

    public static function formatGroupsEloquentCollection(User $user, $collection)
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

            array_push($groups, [
                UtilController::urlFormat($team->name) => [
                    "id" => $team->id,
                    "uuid" => $team->uuid,
                    "name" => $team->name,
                    "url" => UtilController::urlFormat($team->name),
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
        return $groups;
    }

    public static function formatGroupTeam(User $user, Team $team)
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

        return [
            $team->name => [
                "id" => $team->id,
                "uuid" => $team->uuid,
                "name" => $team->name,
                "url" => UtilController::urlFormat($team->name),
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

    public static function getChatsFromEloquentCollection(Collection $collection)
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
