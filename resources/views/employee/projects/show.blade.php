@extends('layouts.app')

@section('title', 'Detail Proyek')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">{{ $project->name }}</h1>
                    <p class="text-muted mb-0">Project Manager: {{ $project->manager->name }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('employee.projects.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Informasi Proyek</h5>
                        </div>
                        <div class="card-body">
                            @if($project->description)
                                <div class="mb-4">
                                    <h6>Deskripsi:</h6>
                                    <div class="border rounded p-3 bg-light">
                                        {!! nl2br(e($project->description)) !!}
                                    </div>
                                </div>
                            @endif
                            
                            @if($project->start_date || $project->end_date)
                                <div class="row mb-4">
                                    @if($project->start_date)
                                        <div class="col-md-6">
                                            <h6>Tanggal Mulai:</h6>
                                            <p>{{ $project->start_date->format('d F Y') }}</p>
                                        </div>
                                    @endif
                                    @if($project->end_date)
                                        <div class="col-md-6">
                                            <h6>Tanggal Selesai:</h6>
                                            <p>{{ $project->end_date->format('d F Y') }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Progress Overview -->
                            @php
                                $totalTasks = $project->tasks->count();
                                $completedTasks = $project->tasks->where('status', 'completed')->count();
                                $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                            @endphp
                            
                            <div class="mb-4">
                                <h6>Progress Proyek:</h6>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>{{ number_format($progress, 1) }}% Selesai</span>
                                    <span class="text-muted">{{ $completedTasks }}/{{ $totalTasks }} tugas</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: {{ $progress }}%" 
                                         aria-valuenow="{{ $progress }}" 
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- My Tasks in this Project -->
                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Tugas Saya dalam Proyek Ini</h5>
                            <span class="badge bg-primary">{{ $myTasks->count() }} tugas</span>
                        </div>
                        <div class="card-body">
                            @if($myTasks->count() > 0)
                                <div class="row">
                                    @foreach($myTasks as $task)
                                        <div class="col-md-6 mb-3">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="card-title mb-0">{{ $task->title }}</h6>
                                                        @if($task->priority === 'high')
                                                            <span class="badge bg-danger">Tinggi</span>
                                                        @elseif($task->priority === 'medium')
                                                            <span class="badge bg-warning">Sedang</span>
                                                        @elseif($task->priority === 'low')
                                                            <span class="badge bg-info">Rendah</span>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($task->description)
                                                        <p class="card-text text-muted small">{{ Str::limit($task->description, 80) }}</p>
                                                    @endif
                                                    
                                                    <div class="mb-2">
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
                                                    
                                                    @if($task->due_date)
                                                        <div class="mb-2">
                                                            <small class="text-muted">
                                                                <i class="fas fa-calendar"></i> 
                                                                {{ $task->due_date->format('d M Y') }}
                                                                @if($task->due_date->isPast() && $task->status !== 'completed')
                                                                    <span class="text-danger">(Terlambat)</span>
                                                                @elseif($task->due_date->diffInDays() <= 3 && $task->status !== 'completed')
                                                                    <span class="text-warning">(Segera)</span>
                                                                @endif
                                                            </small>
                                                        </div>
                                                    @endif
                                                    
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
                                                                                    <i class="fas fa-play text-primary"></i> Mulai
                                                                                </button>
                                                                            </form>
                                                                        </li>
                                                                    @elseif($task->status === 'in_progress')
                                                                        <li>
                                                                            <form action="{{ route('employee.tasks.update-status', $task) }}" method="POST">
                                                                                @csrf
                                                                                @method('PATCH')
                                                                                <input type="hidden" name="status" value="completed">
                                                                                <button type="submit" class="dropdown-item">
                                                                                    <i class="fas fa-check text-success"></i> Selesai
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
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                                    <h6 class="text-muted">Tidak ada tugas</h6>
                                    <p class="text-muted">Anda belum ditugaskan tugas dalam proyek ini.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Detail Proyek</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Status:</strong>
                                <div class="mt-1">
                                    @if($project->status === 'planning')
                                        <span class="badge bg-warning">Perencanaan</span>
                                    @elseif($project->status === 'active')
                                        <span class="badge bg-success">Aktif</span>
                                    @elseif($project->status === 'completed')
                                        <span class="badge bg-primary">Selesai</span>
                                    @elseif($project->status === 'on_hold')
                                        <span class="badge bg-secondary">Ditunda</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Project Manager:</strong>
                                <div class="mt-1 d-flex align-items-center">
                                    @if($project->manager->profile_photo_path)
                                        <img src="{{ Storage::url($project->manager->profile_photo_path) }}" 
                                             alt="{{ $project->manager->name }}" 
                                             class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                             style="width: 32px; height: 32px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    @endif
                                    <div>{{ $project->manager->name }}</div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Dibuat:</strong>
                                <div class="mt-1">{{ $project->created_at->format('d F Y') }}</div>
                            </div>
                            
                            @if($project->priority)
                                <div class="mb-3">
                                    <strong>Prioritas:</strong>
                                    <div class="mt-1">
                                        @if($project->priority === 'high')
                                            <span class="badge bg-danger">Tinggi</span>
                                        @elseif($project->priority === 'medium')
                                            <span class="badge bg-warning">Sedang</span>
                                        @elseif($project->priority === 'low')
                                            <span class="badge bg-info">Rendah</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Team Members -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Tim Proyek</h6>
                        </div>
                        <div class="card-body">
                            @foreach($project->members as $member)
                                <div class="d-flex align-items-center mb-3">
                                    @if($member->profile_photo_path)
                                        <img src="{{ Storage::url($member->profile_photo_path) }}" 
                                             alt="{{ $member->name }}" 
                                             class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">{{ $member->name }}</div>
                                        <small class="text-muted">{{ $member->role->name }}</small>
                                        @if($member->division)
                                            <br><small class="text-muted">{{ $member->division }}</small>
                                        @endif
                                    </div>
                                    @if($member->id === auth()->id())
                                        <span class="badge bg-primary">Anda</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Project Statistics -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Statistik</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <div class="fw-bold text-primary">{{ $project->tasks->count() }}</div>
                                        <small class="text-muted">Total Tugas</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <div class="fw-bold text-success">{{ $project->tasks->where('status', 'completed')->count() }}</div>
                                        <small class="text-muted">Selesai</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row text-center mt-2">
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <div class="fw-bold text-warning">{{ $myTasks->count() }}</div>
                                        <small class="text-muted">Tugas Saya</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <div class="fw-bold text-info">{{ $project->members->count() }}</div>
                                        <small class="text-muted">Anggota Tim</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
