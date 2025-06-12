@extends('layouts.app')

@section('title', 'Project Manager Dashboard')

@section('header')
    <h1 class="h2">Dashboard Project Manager</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('pm.projects.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> Buat Proyek Baru
            </a>
        </div>
    </div>
@endsection

@section('content')
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Total Proyek
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $total_projects ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-project-diagram fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Proyek Aktif
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $active_projects ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cog fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-info border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                Total Tugas
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $total_tasks ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tasks fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-warning border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Tugas Overdue
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $overdue_tasks ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                <div class="list-group list-group-flush">
                     @forelse($tasks_near_deadline as $task)
                        <a href="#" class="list-group-item list-group-item-action">
                            {{ $task->title }} (Deadline: {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }})
                        </a>
                    @empty
                        <div class="list-group-item">Tidak ada tugas.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
{{-- Pastikan Anda punya section 'scripts' di layout utama menggunakan @stack('scripts') --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('projectStatusChart');
    const projectStatusLabels = @json($project_status_labels);
    const projectStatusValues = @json($project_status_values);

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: projectStatusLabels,
            datasets: [{
                label: 'Jumlah Proyek',
                data: projectStatusValues,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                ],
                hoverOffset: 4
            }]
        },
    });
</script>
@endpush