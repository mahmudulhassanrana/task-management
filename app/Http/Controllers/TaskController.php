<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{

    public function index()
    {
        return Inertia::render('Tasks/Index', [
            'tasks' => Task::with('project')->orderBy('priority', 'asc')->get(),
            'projects' => Project::orderBy('priority', 'asc')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'project_id' => 'required',
            'status' => 'in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
        ]);

        $position = Task::orderBy('priority', 'desc')->first();

        if (empty($position)) {
            $validated['priority'] = 1;
        } else {
            $validated['priority'] = $position['priority'] + 1;
        }
        Task::create($validated);

        return redirect()->back()->with('success', 'Task created successfully.');
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'status' => 'in:pending,in_progress,completed',
            'project_id' => 'required',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        return redirect()->back()->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted.');
    }

    public function complete(Task $task)
    {
        $task->update([
           'status' => 'completed'
        ]);
        return redirect()->back()->with('success', 'Task completed.');
    }

    public function reorder(Request $request)
    {
        $tasks = $request->all();
        foreach($tasks as $index => $taskId) {
            Task::where('id', $taskId)->update(['priority' => $index + 1]);
        }
        return response()->json(['status' => 'success']);
    }
}
