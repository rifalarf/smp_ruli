@extends('layouts.app')

@section('title', 'Proyek Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Proyek Saya</h1>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter"></i> Filter Status
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('employee.projects.index') }}">Semua</a></li>
                            <li><a class="dropdown-item" href="{{ route('employee.projects.index', ['status' => 'planning']) }}">Perencanaan</a></li>
                            <li><a class="dropdown-item" href="{{ route('employee.projects.index', ['status' => 'active']) }}">Aktif</a></li>
                            <li><a class="dropdown-item" href="{{ route('employee.projects.index', ['status' => 'completed']) }}">Selesai</a></li>
                            <li><a class="dropdown-item" href="{{ route('employee.projects.index', ['status' => 'on_hold']) }}">Ditunda</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                @forelse($projects as $project)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 project-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">{{ $project->name }}</h6>
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
                            
                            <div class="card-body">
                                @if($project->description)
                                    <p class="card-text">{{ Str::limit($project->description, 120) }}</p>
                                @endif
                                
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-user"></i> 
                                        PM: {{ $project->manager->name }}
                                    </small>
                                </div>
                                
                                @if($project->start_date || $project->end_date)
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i> 
                                            @if($project->start_date)
                                                {{ $project->start_date->format('d M Y') }}
                                            @endif
                                            @if($project->start_date && $project->end_date)
                                                - 
                                            @endif
                                            @if($project->end_date)
                                                {{ $project->end_date->format('d M Y') }}
                                            @endif
                                        </small>
                                    </div>
                                @endif
                                
                                <!-- Progress Bar -->
                                @php
                                    $totalTasks = $project->tasks->count();
                                    $completedTasks = $project->tasks->where('status', 'completed')->count();
                                    $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                                @endphp
                                
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="text-muted">Progress</small>
                                        <small class="text-muted">{{ number_format($progress, 1) }}%</small>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ $progress }}%" 
                                             aria-valuenow="{{ $progress }}" 
                                             aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted">{{ $completedTasks }}/{{ $totalTasks }} tugas selesai</small>
                                </div>
                                
                                <!-- My Tasks in this Project -->
                                @php
                                    $myTasks = $project->tasks->where('assigned_to', auth()->id());
                                    $myCompletedTasks = $myTasks->where('status', 'completed')->count();
                                    $myPendingTasks = $myTasks->whereIn('status', ['pending', 'in_progress'])->count();
                                @endphp
                                
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="border rounded p-2">
                                            <div class="fw-bold text-primary">{{ $myTasks->count() }}</div>
                                            <small class="text-muted">Total Tugas</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border rounded p-2">
                                            <div class="fw-bold text-warning">{{ $myPendingTasks }}</div>
                                            <small class="text-muted">Pending</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border rounded p-2">
                                            <div class="fw-bold text-success">{{ $myCompletedTasks }}</div>
                                            <small class="text-muted">Selesai</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('employee.projects.show', $project) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    
                                    <div class="d-flex align-items-center">
                                        <small class="text-muted me-2">{{ $project->members->count() }} anggota</small>
                                        <div class="avatar-group">
                                            @foreach($project->members->take(3) as $member)
                                                @if($member->profile_photo_path)
                                                    <img src="{{ Storage::url($member->profile_photo_path) }}" 
                                                         alt="{{ $member->name }}" 
                                                         class="rounded-circle border border-2 border-white" 
                                                         style="width: 24px; height: 24px; margin-left: -8px; object-fit: cover;"
                                                         title="{{ $member->name }}">
                                                @else
                                                    <div class="bg-secondary rounded-circle border border-2 border-white d-inline-flex align-items-center justify-content-center" 
                                                         style="width: 24px; height: 24px; margin-left: -8px;" 
                                                         title="{{ $member->name }}">
                                                        <i class="fas fa-user text-white" style="font-size: 10px;"></i>
                                                    </div>
                                                @endif
                                            @endforeach
                                            @if($project->members->count() > 3)
                                                <div class="bg-light text-muted rounded-circle border border-2 border-white d-inline-flex align-items-center justify-content-center" 
                                                     style="width: 24px; height: 24px; margin-left: -8px; font-size: 10px;">
                                                    +{{ $project->members->count() - 3 }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Tidak ada proyek</h5>
                                <p class="text-muted">Anda belum tergabung dalam proyek apapun.</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            
            @if($projects->count() > 0)
                <div class="d-flex justify-content-center mt-4">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.project-card {
    transition: transform 0.2s ease-in-out;
}

.project-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.avatar-group {
    display: flex;
    align-items: center;
}
</style>
@endpush
