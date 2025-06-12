<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskUpdate;
use App\Notifications\TaskUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskUpdateController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'description' => 'required|string|min:10',
            'hours_spent' => 'nullable|numeric|min:0|max:24',
            'status_change' => 'nullable|in:Belum Dikerjakan,In Progress,Selesai,Revisi,Blocked'
        ]);

        $taskUpdate = $task->updates()->create($validated + [
            'user_id' => Auth::id()
        ]);

        // Update status task jika ada perubahan status
        if ($request->filled('status_change')) {
            $task->update(['status' => $validated['status_change']]);
        }

        // Kirim notifikasi ke Project Manager
        $projectManager = $task->project->projectManager;
        $projectManager->notify(new TaskUpdated($taskUpdate));

        return redirect()->back()
            ->with('success', 'Progress task berhasil dilaporkan dan notifikasi dikirim ke Project Manager.');
    }

    public function edit(Task $task, TaskUpdate $update)
    {
        // Pastikan update ini milik user yang login
        if ($update->user_id !== Auth::id()) {
            abort(403);
        }

        $this->authorize('update', $task);

        return view('employee.task-updates.edit', compact('task', 'update'));
    }

    public function update(Request $request, Task $task, TaskUpdate $update)
    {
        // Pastikan update ini milik user yang login
        if ($update->user_id !== Auth::id()) {
            abort(403);
        }

        $this->authorize('update', $task);

        $validated = $request->validate([
            'description' => 'required|string|min:10',
            'hours_spent' => 'nullable|numeric|min:0|max:24',
        ]);

        $update->update($validated);

        return redirect()->route('employee.tasks.show', $task)
            ->with('success', 'Update progress berhasil diperbarui.');
    }
}
