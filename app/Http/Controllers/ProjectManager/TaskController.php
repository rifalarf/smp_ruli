<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Notifications\TaskAssigned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        // Tampilkan semua task dari proyek yang di-manage PM ini
        $tasks = Task::whereHas('project', function ($query) {
            $query->where('project_manager_id', Auth::id());
        })->with(['project', 'assignedTo', 'assignedBy'])
          ->latest()
          ->paginate(15);

        return view('pm.tasks.index', compact('tasks'));
    }

    public function create(Project $project)
    {
        $this->authorize('view', $project);
        
        // Dapatkan semua member proyek + employees lain untuk assignment
        $employees = User::whereHas('role', function ($query) {
            $query->where('slug', 'employee');
        })->get();

        // Dapatkan parent tasks untuk hierarki
        $parentTasks = $project->tasks()->whereNull('parent_task_id')->get();

        return view('pm.tasks.create', compact('project', 'employees', 'parentTasks'));
    }

    public function store(Request $request, Project $project)
    {
        $this->authorize('view', $project);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to_id' => 'required|exists:users,id',
            'deadline' => 'required|date|after:today',
            'parent_task_id' => 'nullable|exists:tasks,id',
            'status' => 'required|in:Belum Dikerjakan,In Progress,Selesai,Revisi,Blocked'
        ]);

        $task = $project->tasks()->create($validated + [
            'assigned_by_id' => Auth::id()
        ]);

        // Kirim notifikasi ke karyawan yang ditugaskan
        $assignedUser = User::find($validated['assigned_to_id']);
        $assignedUser->notify(new TaskAssigned($task));

        return redirect()->route('pm.projects.show', $project)
            ->with('success', 'Task berhasil dibuat dan ditugaskan.');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        $task->load(['project', 'assignedTo', 'assignedBy', 'updates.user', 'comments.user', 'attachments']);
        
        return view('pm.tasks.show', compact('task'));
    }

    public function edit(Project $project, Task $task)
    {
        $this->authorize('update', $task);
        
        $employees = User::whereHas('role', function ($query) {
            $query->where('slug', 'employee');
        })->get();

        $parentTasks = $project->tasks()
            ->whereNull('parent_task_id')
            ->where('id', '!=', $task->id)
            ->get();

        return view('pm.tasks.edit', compact('project', 'task', 'employees', 'parentTasks'));
    }

    public function update(Request $request, Project $project, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to_id' => 'required|exists:users,id',
            'deadline' => 'required|date',
            'parent_task_id' => 'nullable|exists:tasks,id',
            'status' => 'required|in:Belum Dikerjakan,In Progress,Selesai,Revisi,Blocked'
        ]);

        // Cek apakah assigned_to berubah
        $oldAssignedTo = $task->assigned_to_id;
        $task->update($validated);

        // Jika assigned_to berubah, kirim notifikasi
        if ($oldAssignedTo != $validated['assigned_to_id']) {
            $newAssignedUser = User::find($validated['assigned_to_id']);
            $newAssignedUser->notify(new TaskAssigned($task->fresh()));
        }

        return redirect()->route('pm.projects.show', $project)
            ->with('success', 'Task berhasil diperbarui.');
    }

    public function destroy(Project $project, Task $task)
    {
        $this->authorize('delete', $task);
        
        $task->delete();

        return redirect()->route('pm.projects.show', $project)
            ->with('success', 'Task berhasil dihapus.');
    }

    public function approve(Task $task)
    {
        $this->authorize('update', $task);
        
        $task->update(['status' => 'Selesai']);

        return redirect()->back()
            ->with('success', 'Task berhasil disetujui dan ditandai selesai.');
    }

    public function reject(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        
        $request->validate([
            'rejection_note' => 'required|string|max:500'
        ]);

        $task->update(['status' => 'Revisi']);

        // Buat task update dengan catatan penolakan
        $task->updates()->create([
            'user_id' => Auth::id(),
            'description' => 'Task ditolak oleh PM: ' . $request->rejection_note,
            'status_change' => 'Revisi'
        ]);

        return redirect()->back()
            ->with('success', 'Task berhasil ditolak dan dikembalikan untuk revisi.');
    }
}
