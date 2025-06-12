<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Report;
use Illuminate\Support\Facades\DB;

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

        // Pastikan pengguna memiliki peran sebelum mengakses slug
        if (!$user->role) {
            // Redirect atau tampilkan pesan error jika peran tidak ada
            // Ini untuk mencegah error jika relasi role tidak ditemukan
            Auth::logout();
            return redirect('/login')->withErrors('Peran pengguna tidak valid. Silakan hubungi administrator.');
        }

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
            'active_projects' => Project::where('status', 'In Progress')->count(),
            'pending_reports_count' => Report::where('status', 'Menunggu Persetujuan')->count(),
            'pending_reports' => Report::with(['project', 'submittedBy'])
                ->where('status', 'Menunggu Persetujuan')
                ->latest()
                ->take(5)
                ->get(),
        ];
        return view('admin.dashboard', $data);
    }

    /**
     * Menyiapkan data dan menampilkan dashboard untuk Project Manager.
     */
    private function pmDashboard()
    {
        $managedProjects = Project::where('project_manager_id', Auth::id())->with('tasks')->get();
        $projectIds = $managedProjects->pluck('id');

        $tasks_need_approval = Task::whereIn('project_id', $projectIds)
                                     ->where('status', 'Selesai')
                                     ->get();

        $tasks_near_deadline = Task::whereIn('project_id', $projectIds)
                                     ->where('status', '!=', 'Selesai')
                                     ->where('deadline', '>=', now())
                                     ->where('deadline', '<=', now()->addDays(7))
                                     ->orderBy('deadline', 'asc')
                                     ->get();
                                     
        $projectStatusData = $managedProjects->groupBy('status')->map->count();

        $data = [
            'total_projects' => $managedProjects->count(),
            'active_projects' => $managedProjects->where('status', 'In Progress')->count(),
            'total_tasks' => Task::whereIn('project_id', $projectIds)->count(),
            'overdue_tasks' => Task::whereIn('project_id', $projectIds)->where('deadline', '<', now())->where('status', '!=', 'Selesai')->count(),
            'projects' => $managedProjects->take(5),
            'tasks_need_approval' => $tasks_need_approval,
            'tasks_near_deadline' => $tasks_near_deadline,
            'project_status_labels' => $projectStatusData->keys(),
            'project_status_values' => $projectStatusData->values(),
        ];

        return view('pm.dashboard', $data);
    }

    /**
     * Menyiapkan data dan menampilkan dashboard untuk Karyawan.
     * INI ADALAH FUNGSI YANG DIPERBAIKI
     */
    private function employeeDashboard()
    {
        // Query dasar untuk tugas milik pengguna yang sedang login
        $myTasksQuery = Task::where('assigned_to_id', Auth::id());

        // FIX: Menggunakan CASE statement untuk custom sorting yang kompatibel dengan SQLite & MySQL
        $orderCase = "CASE
                        WHEN status = 'In Progress' THEN 1
                        WHEN status = 'Belum Dikerjakan' THEN 2
                        WHEN status = 'Revisi' THEN 3
                        WHEN status = 'Blocked' THEN 4
                        WHEN status = 'Selesai' THEN 5
                        ELSE 6
                      END";

        // Mengambil semua tugas dengan urutan yang sudah diperbaiki
        $tasks = $myTasksQuery->orderByRaw($orderCase)
                             ->orderBy('deadline', 'asc')
                             ->get();

        // Menghitung statistik berdasarkan koleksi yang sudah diambil
        $stats = [
            'in_progress' => $tasks->where('status', 'In Progress')->count(),
            'pending' => $tasks->where('status', 'Belum Dikerjakan')->count(),
            'completed' => $tasks->where('status', 'Selesai')->count(),
            'revision' => $tasks->where('status', 'Revisi')->count(),
        ];

        $data = [
            'tasks' => $tasks,
            'stats' => $stats,
        ];
        
        return view('employee.dashboard', $data);
    }
}