<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class FileController extends Controller
{
    function show($url)
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
