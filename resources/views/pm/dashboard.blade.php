@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Dashboard Project Manager</h1>

    <div class="row">
        {{-- Grafik Progres Proyek --}}
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">Progres Proyek</div>
                <div class="card-body">
                    <canvas id="projectStatusChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Tugas Butuh Approval & Mendekati Deadline --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">Tugas Butuh Approval</div>
                <div class="list-group list-group-flush">
                    @forelse($tasks_need_approval as $task)
                        <a href="#" class="list-group-item list-group-item-action">{{ $task->title }} - {{ $task->project->name }}</a>
                    @empty
                        <div class="list-group-item">Tidak ada tugas.</div>
                    @endforelse
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header text-bg-warning">Tugas Mendekati Deadline</div>
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