@extends('layouts.app')

@section('title', 'Tugas Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Tugas Saya</h1>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter"></i> Filter Status
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('employee.tasks.index') }}">Semua</a></li>
                            <li><a class="dropdown-item" href="{{ route('employee.tasks.index', ['status' => 'pending']) }}">Pending</a></li>
                            <li><a class="dropdown-item" href="{{ route('employee.tasks.index', ['status' => 'in_progress']) }}">Sedang Dikerjakan</a></li>
                            <li><a class="dropdown-item" href="{{ route('employee.tasks.index', ['status' => 'completed']) }}">Selesai</a></li>
                            <li><a class="dropdown-item" href="{{ route('employee.tasks.index', ['status' => 'on_hold']) }}">Ditunda</a></li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-sort"></i> Urutkan
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('employee.tasks.index', ['sort' => 'due_date']) }}">Tenggat Waktu</a></li>
                            <li><a class="dropdown-item" href="{{ route('employee.tasks.index', ['sort' => 'priority']) }}">Prioritas</a></li>
                            <li><a class="dropdown-item" href="{{ route('employee.tasks.index', ['sort' => 'created_at']) }}">Terbaru</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Task Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $stats['pending'] }}</h4>
                                    <p class="mb-0">Pending</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $stats['in_progress'] }}</h4>
                                    <p class="mb-0">Sedang Dikerjakan</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-play fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $stats['completed'] }}</h4>
                                    <p class="mb-0">Selesai</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-secondary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $stats['on_hold'] }}</h4>
                                    <p class="mb-0">Ditunda</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-pause fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    @if($tasks->count() > 0)
                        <div class="row">
                            @foreach($tasks as $task)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 task-card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                @if($task->priority === 'high')
                                                    <span class="badge bg-danger me-2">Tinggi</span>
                                                @elseif($task->priority === 'medium')
                                                    <span class="badge bg-warning me-2">Sedang</span>
                                                @elseif($task->priority === 'low')
                                                    <span class="badge bg-info me-2">Rendah</span>
                                                @endif
                                                
                                                @if($task->status === 'pending')
                                                    <span class="badge bg-secondary">Pending</span>
                                                @elseif($task->status === 'in_progress')
                                                    <span class="badge bg-primary">Sedang Dikerjakan</span>
                                                @elseif($task->status === 'completed')
                                                    <span class="badge bg-success">Selesai</span>
                                                @elseif($task->status === 'on_hold')
                                                    <span class="badge bg-warning">Ditunda</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $task->title }}</h6>
                                            <p class="card-text text-muted small mb-2">
                                                Proyek: {{ $task->project->name }}
                                            </p>
                                            
                                            @if($task->description)
                                                <p class="card-text">{{ Str::limit($task->description, 100) }}</p>
                                            @endif
                                            
                                            @if($task->due_date)
                                                <div class="mb-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar"></i> 
                                                        Tenggat: {{ $task->due_date->format('d M Y') }}
                                                        @if($task->due_date->isPast() && $task->status !== 'completed')
                                                            <span class="text-danger">(Terlambat)</span>
                                                        @elseif($task->due_date->diffInDays() <= 3 && $task->status !== 'completed')
                                                            <span class="text-warning">(Segera)</span>
                                                        @endif
                                                    </small>
                                                </div>
                                            @endif
                                            
                                            @if($task->estimated_hours)
                                                <div class="mb-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock"></i> 
                                                        Estimasi: {{ $task->estimated_hours }} jam
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="card-footer bg-transparent">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="{{ route('employee.tasks.show', $task) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                                
                                                @if($task->status !== 'completed')
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                                type="button" data-bs-toggle="dropdown">
                                                            <i class="fas fa-cog"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @if($task->status === 'pending')
                                                                <li>
                                                                    <form action="{{ route('employee.tasks.update-status', $task) }}" method="POST">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <input type="hidden" name="status" value="in_progress">
                                                                        <button type="submit" class="dropdown-item">
                                                                            <i class="fas fa-play text-primary"></i> Mulai Kerjakan
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @elseif($task->status === 'in_progress')
                                                                <li>
                                                                    <form action="{{ route('employee.tasks.update-status', $task) }}" method="POST">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <input type="hidden" name="status" value="completed">
                                                                        <button type="submit" class="dropdown-item" 
                                                                                onclick="return confirm('Yakin tugas sudah selesai?')">
                                                                            <i class="fas fa-check text-success"></i> Tandai Selesai
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                                <li>
                                                                    <form action="{{ route('employee.tasks.update-status', $task) }}" method="POST">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <input type="hidden" name="status" value="on_hold">
                                                                        <button type="submit" class="dropdown-item">
                                                                            <i class="fas fa-pause text-warning"></i> Tunda
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @elseif($task->status === 'on_hold')
                                                                <li>
                                                                    <form action="{{ route('employee.tasks.update-status', $task) }}" method="POST">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <input type="hidden" name="status" value="in_progress">
                                                                        <button type="submit" class="dropdown-item">
                                                                            <i class="fas fa-play text-primary"></i> Lanjutkan
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $tasks->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada tugas</h5>
                            <p class="text-muted">Belum ada tugas yang ditugaskan kepada Anda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.task-card {
    transition: transform 0.2s ease-in-out;
}

.task-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>
@endpush
