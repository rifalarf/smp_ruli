@extends('layouts.app')

@section('title', 'Semua Tugas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Semua Tugas</h1>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter"></i> Filter Status
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('pm.tasks.index') }}">Semua</a></li>
                            <li><a class="dropdown-item" href="{{ route('pm.tasks.index', ['status' => 'pending']) }}">Pending</a></li>
                            <li><a class="dropdown-item" href="{{ route('pm.tasks.index', ['status' => 'in_progress']) }}">Sedang Dikerjakan</a></li>
                            <li><a class="dropdown-item" href="{{ route('pm.tasks.index', ['status' => 'completed']) }}">Selesai</a></li>
                            <li><a class="dropdown-item" href="{{ route('pm.tasks.index', ['status' => 'on_hold']) }}">Ditunda</a></li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-project-diagram"></i> Filter Proyek
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('pm.tasks.index') }}">Semua Proyek</a></li>
                            @foreach($projects as $project)
                                <li><a class="dropdown-item" href="{{ route('pm.tasks.index', ['project' => $project->id]) }}">{{ $project->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    @if($tasks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tugas</th>
                                        <th>Proyek</th>
                                        <th>Ditugaskan Kepada</th>
                                        <th>Prioritas</th>
                                        <th>Status</th>
                                        <th>Tenggat Waktu</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tasks as $task)
                                        <tr>
                                            <td>
                                                <strong>{{ $task->title }}</strong>
                                                @if($task->description)
                                                    <br><small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('pm.projects.show', $task->project) }}" 
                                                   class="text-decoration-none">
                                                    {{ $task->project->name }}
                                                </a>
                                            </td>
                                            <td>
                                                @if($task->assignedTo)
                                                    <div class="d-flex align-items-center">
                                                        @if($task->assignedTo->profile_photo_path)
                                                            <img src="{{ Storage::url($task->assignedTo->profile_photo_path) }}" 
                                                                 alt="{{ $task->assignedTo->name }}" 
                                                                 class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                                                 style="width: 32px; height: 32px;">
                                                                <i class="fas fa-user text-white"></i>
                                                            </div>
                                                        @endif
                                                        <div>{{ $task->assignedTo->name }}</div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Belum ditugaskan</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($task->priority === 'high')
                                                    <span class="badge bg-danger">Tinggi</span>
                                                @elseif($task->priority === 'medium')
                                                    <span class="badge bg-warning">Sedang</span>
                                                @elseif($task->priority === 'low')
                                                    <span class="badge bg-info">Rendah</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($task->status === 'pending')
                                                    <span class="badge bg-secondary">Pending</span>
                                                @elseif($task->status === 'in_progress')
                                                    <span class="badge bg-primary">Sedang Dikerjakan</span>
                                                @elseif($task->status === 'completed')
                                                    <span class="badge bg-success">Selesai</span>
                                                @elseif($task->status === 'on_hold')
                                                    <span class="badge bg-warning">Ditunda</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($task->due_date)
                                                    <div>{{ $task->due_date->format('d M Y') }}</div>
                                                    @if($task->due_date->isPast() && $task->status !== 'completed')
                                                        <small class="text-danger">Terlambat</small>
                                                    @elseif($task->due_date->diffInDays() <= 3 && $task->status !== 'completed')
                                                        <small class="text-warning">Segera</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Tidak ada</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('pm.tasks.show', $task) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('pm.projects.tasks.edit', [$task->project, $task]) }}" 
                                                       class="btn btn-sm btn-outline-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $tasks->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada tugas</h5>
                            <p class="text-muted">Belum ada tugas yang dibuat untuk proyek yang Anda kelola.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
