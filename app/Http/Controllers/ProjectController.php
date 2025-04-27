<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index()
    {
        return Inertia::render('Tasks/Project', [
            'projects' => Project::orderBy('priority', 'asc')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $position = Project::orderBy('priority', 'desc')->first();

        if (empty($position)) {
            $validated['priority'] = 1;
        } else {
            $validated['priority'] = $position['priority'] + 1;
        }

        Project::create($validated);

        return redirect()->back()->with('success', 'Project created successfully.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Project::where('id', $request->id)->update($validated);

        return redirect()->back()->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->back()->with('success', 'Project deleted.');
    }


    public function reorder(Request $request)
    {
        $project = $request->all();
        foreach($project as $index => $projectId) {
            Project::where('id', $projectId)->update(['priority' => $index + 1]);
        }
        return response()->json(['status' => 'success']);
    }
}
