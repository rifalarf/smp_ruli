<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::where('assigned_to_id', Auth::id())
            ->with(['project', 'assignedBy']);

        // Filter berdasarkan status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan proyek jika ada
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $tasks = $query->orderByRaw("FIELD(status, 'In Progress', 'Belum Dikerjakan', 'Revisi', 'Blocked', 'Selesai')")
                      ->orderBy('deadline', 'asc')
                      ->paginate(15);

        // Data untuk filter
        $projects = Project::whereHas('members', function ($q) {
            $q->where('users.id', Auth::id());
        })->orWhereHas('tasks', function ($q) {
            $q->where('assigned_to_id', Auth::id());
        })->get();

        $statuses = ['Belum Dikerjakan', 'In Progress', 'Selesai', 'Revisi', 'Blocked'];

        return view('employee.tasks.index', compact('tasks', 'projects', 'statuses'));
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        
        $task->load(['project', 'assignedBy', 'updates.user', 'comments.user', 'attachments']);
        
        return view('employee.tasks.show', compact('task'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'status' => 'required|in:In Progress,Selesai,Blocked',
            'note' => 'nullable|string|max:500'
        ]);

        $oldStatus = $task->status;
        $task->update(['status' => $validated['status']]);

        // Buat task update record
        $task->updates()->create([
            'user_id' => Auth::id(),
            'description' => $request->note ?? 'Status diubah dari ' . $oldStatus . ' ke ' . $validated['status'],
            'status_change' => $validated['status']
        ]);

        $message = 'Status task berhasil diperbarui.';
        
        // Jika task diselesaikan, beri pesan khusus
        if ($validated['status'] === 'Selesai') {
            $message = 'Task telah ditandai selesai dan menunggu approval dari Project Manager.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function projects()
    {
        // Proyek yang user ini ikuti sebagai anggota tim
        $projects = Project::whereHas('members', function ($query) {
            $query->where('users.id', Auth::id());
        })->with(['projectManager', 'tasks' => function ($query) {
            $query->where('assigned_to_id', Auth::id());
        }])->get();

        return view('employee.projects.index', compact('projects'));
    }

    public function showProject(Project $project)
    {
        // Pastikan user adalah anggota proyek ini
        if (!$project->members->contains(Auth::id()) && 
            !$project->tasks()->where('assigned_to_id', Auth::id())->exists()) {
            abort(403, 'Anda tidak memiliki akses ke proyek ini.');
        }

        $project->load(['projectManager', 'members', 'tasks' => function ($query) {
            $query->where('assigned_to_id', Auth::id());
        }]);

        return view('employee.projects.show', compact('project'));
    }
}
