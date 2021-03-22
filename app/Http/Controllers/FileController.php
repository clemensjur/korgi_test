<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use SoareCostin\FileVault\Facades\FileVault;
use Illuminate\Support\Str;
use Inertia\Inertia;

class FileController extends Controller
{
    function show(Request $request, $url)
    {
        $team = Team::where("url", $url)->first();
        $dirName = 'files/' . $team->name . "_" . $team->id;
        $files = Storage::disk("ftp")->files($dirName);
        return Inertia::render("Group/Files", [
            "group" => $team,
            "files" => $files
        ]);
    }

    function store(Request $request)
    {
        $group = Team::find($request->groupId);
        $dirName = 'files/' . $group->name . "_" . $group->id;
        if (!Storage::exists($dirName)) {
            Storage::makeDirectory($dirName);
        }
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $path = $request->file('file')->storeAs($dirName, $request->file('file')->getClientOriginalName());

            /*
            if ($filename) {
                FileVault::disk("ftp")->encrypt(
                    $dirName . "/" . $request->file('file')->getClientOriginalName(),
                    $dirName . "/" . $request->file('file')->getClientOriginalName() . ".enc"
                );
            }
            */
        }

        return $path;
    }

    function download(Request $request)
    {
        $group = Team::find($request->groupId);
        $filename = $request->filename;
        $groupName = $group->name;
        $groupId = $group->id;
        $filePath = 'files/' . $groupName . "_" . $groupId . '/' . $filename;

        if (!Storage::disk("ftp")->exists($filePath)) {
            abort(404, "File not found!");
        }
        ob_end_clean();
        return Storage::get($filePath);
    }
}
