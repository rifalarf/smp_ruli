<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Report;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard yang sesuai berdasarkan peran pengguna.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->role->slug;

        switch ($role) {
            case 'admin':
                return $this->adminDashboard();
            case 'pm':
                return $this->pmDashboard();
            case 'employee':
                return $this->employeeDashboard();
            default:
                // Fallback ke halaman utama jika peran tidak dikenali
                return redirect('/');
        }
    }

    /**
     * Menyiapkan data dan menampilkan dashboard untuk Administrator.
     */
    private function adminDashboard()
    {
        $data = [
            'total_users' => User::count(),
            'total_projects' => Project::count(),
            'pending_reports' => Report::where('status', 'Menunggu Persetujuan')->get(),
        ];
        return view('admin.dashboard', $data);
    }

    /**
     * Menyiapkan data dan menampilkan dashboard untuk Project Manager.
     */
    private function pmDashboard()
    {
        $managedProjects = Project::where('project_manager_id', Auth::id())->with('tasks')->get();

        $tasks_need_approval = Task::whereIn('project_id', $managedProjects->pluck('id'))
                                     ->where('status', 'Selesai') // Asumsi 'Selesai' dari karyawan butuh approval PM
                                     ->get();

        $tasks_near_deadline = Task::whereIn('project_id', $managedProjects->pluck('id'))
                                     ->where('status', '!=', 'Selesai')
                                     ->where('deadline', '>=', now())
                                     ->where('deadline', '<=', now()->addDays(7))
                                     ->orderBy('deadline', 'asc')
                                     ->get();

        // Data untuk Chart.js (contoh: status proyek)
        $projectStatusData = $managedProjects->groupBy('status')->map->count();

        $data = [
            'projects' => $managedProjects,
            'tasks_need_approval' => $tasks_need_approval,
            'tasks_near_deadline' => $tasks_near_deadline,
            'project_status_labels' => $projectStatusData->keys(),
            'project_status_values' => $projectStatusData->values(),
        ];

        return view('pm.dashboard', $data);
    }

    /**
     * Menyiapkan data dan menampilkan dashboard untuk Karyawan.
     */
    private function employeeDashboard()
    {
        $my_tasks = Task::where('assigned_to_id', Auth::id())
                        ->orderByRaw("FIELD(status, 'In Progress', 'Belum Dikerjakan', 'Revisi', 'Blocked', 'Selesai')")
                        ->orderBy('deadline', 'asc')
                        ->get();

        $data = [
            'tasks' => $my_tasks,
            'in_progress_tasks_count' => $my_tasks->where('status', 'In Progress')->count(),
        ];
        return view('employee.dashboard', $data);
    }
}