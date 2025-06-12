@extends('layouts.app')

@section('title', 'Detail Tugas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">{{ $task->title }}</h1>
                    <p class="text-muted mb-0">Proyek: {{ $task->project->name }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('pm.projects.tasks.edit', [$task->project, $task]) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('pm.projects.show', $task->project) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Detail Tugas</h5>
                        </div>
                        <div class="card-body">
                            @if($task->description)
                                <div class="mb-4">
                                    <h6>Deskripsi:</h6>
                                    <div class="border rounded p-3 bg-light">
                                        {!! nl2br(e($task->description)) !!}
                                    </div>
                                </div>
                            @endif
                            
                            @if($task->requirements)
                                <div class="mb-4">
                                    <h6>Persyaratan:</h6>
                                    <div class="border rounded p-3">
                                        {!! nl2br(e($task->requirements)) !!}
                                    </div>
                                </div>
                            @endif
                            
                            @if($task->attachments && $task->attachments->count() > 0)
                                <div class="mb-4">
                                    <h6>Lampiran:</h6>
                                    <div class="row">
                                        @foreach($task->attachments as $attachment)
                                            <div class="col-md-6 mb-2">
                                                <div class="border rounded p-2 d-flex align-items-center">
                                                    <i class="fas fa-file me-2"></i>
                                                    <div class="flex-grow-1">
                                                        <div>{{ $attachment->original_name }}</div>
                                                        <small class="text-muted">{{ number_format($attachment->file_size / 1024, 2) }} KB</small>
                                                    </div>
                                                    <a href="{{ Storage::url($attachment->file_path) }}" 
                                                       target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($task->updates && $task->updates->count() > 0)
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Update Tugas</h5>
                            </div>
                            <div class="card-body">
                                @foreach($task->updates->sortByDesc('created_at') as $update)
                                    <div class="border-start border-3 border-primary ps-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="d-flex align-items-center">
                                                @if($update->user->profile_photo_path)
                                                    <img src="{{ Storage::url($update->user->profile_photo_path) }}" 
                                                         alt="{{ $update->user->name }}" 
                                                         class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                                         style="width: 32px; height: 32px;">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $update->user->name }}</strong>
                                                    <br><small class="text-muted">{{ $update->created_at->format('d M Y H:i') }}</small>
                                                </div>
                                            </div>
                                            @if($update->status)
                                                <span class="badge bg-info">Status: {{ ucfirst(str_replace('_', ' ', $update->status)) }}</span>
                                            @endif
                                        </div>
                                        
                                        @if($update->description)
                                            <p class="mb-2">{!! nl2br(e($update->description)) !!}</p>
                                        @endif
                                        
                                        @if($update->hours_worked)
                                            <small class="text-muted">Jam kerja: {{ $update->hours_worked }} jam</small>
                                        @endif
                                        
                                        @if($update->attachments && $update->attachments->count() > 0)
                                            <div class="mt-2">
                                                <small class="text-muted">Lampiran:</small>
                                                @foreach($update->attachments as $attachment)
                                                    <a href="{{ Storage::url($attachment->file_path) }}" 
                                                       target="_blank" class="btn btn-sm btn-outline-secondary ms-1">
                                                        <i class="fas fa-file"></i> {{ $attachment->original_name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    @if($task->comments && $task->comments->count() > 0)
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Komentar</h5>
                            </div>
                            <div class="card-body">
                                @foreach($task->comments->sortBy('created_at') as $comment)
                                    <div class="d-flex mb-3">
                                        @if($comment->user->profile_photo_path)
                                            <img src="{{ Storage::url($comment->user->profile_photo_path) }}" 
                                                 alt="{{ $comment->user->name }}" 
                                                 class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <div class="bg-light rounded p-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <strong>{{ $comment->user->name }}</strong>
                                                    <small class="text-muted">{{ $comment->created_at->format('d M Y H:i') }}</small>
                                                </div>
                                                <p class="mb-0">{!! nl2br(e($comment->content)) !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Informasi Tugas</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Status:</strong>
                                <div class="mt-1">
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
                            
                            <div class="mb-3">
                                <strong>Prioritas:</strong>
                                <div class="mt-1">
                                    @if($task->priority === 'high')
                                        <span class="badge bg-danger">Tinggi</span>
                                    @elseif($task->priority === 'medium')
                                        <span class="badge bg-warning">Sedang</span>
                                    @elseif($task->priority === 'low')
                                        <span class="badge bg-info">Rendah</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Ditugaskan Kepada:</strong>
                                <div class="mt-1">
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
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Dibuat Oleh:</strong>
                                <div class="mt-1">{{ $task->createdBy->name }}</div>
                            </div>
                            
                            @if($task->start_date)
                                <div class="mb-3">
                                    <strong>Tanggal Mulai:</strong>
                                    <div class="mt-1">{{ $task->start_date->format('d F Y') }}</div>
                                </div>
                            @endif
                            
                            @if($task->due_date)
                                <div class="mb-3">
                                    <strong>Tenggat Waktu:</strong>
                                    <div class="mt-1">
                                        {{ $task->due_date->format('d F Y') }}
                                        @if($task->due_date->isPast() && $task->status !== 'completed')
                                            <br><small class="text-danger">Terlambat</small>
                                        @elseif($task->due_date->diffInDays() <= 3 && $task->status !== 'completed')
                                            <br><small class="text-warning">Segera</small>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            @if($task->estimated_hours)
                                <div class="mb-3">
                                    <strong>Estimasi Jam:</strong>
                                    <div class="mt-1">{{ $task->estimated_hours }} jam</div>
                                </div>
                            @endif
                            
                            @if($task->updates->sum('hours_worked') > 0)
                                <div class="mb-3">
                                    <strong>Total Jam Kerja:</strong>
                                    <div class="mt-1">{{ $task->updates->sum('hours_worked') }} jam</div>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <strong>Dibuat:</strong>
                                <div class="mt-1">{{ $task->created_at->format('d F Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    @if($task->assignedTo && $task->status !== 'completed')
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Aksi Cepat</h6>
                            </div>
                            <div class="card-body">
                                @if($task->status === 'pending')
                                    <form action="{{ route('pm.tasks.approve', $task) }}" method="POST" class="mb-2">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success w-100"
                                                onclick="return confirm('Yakin ingin menyetujui tugas ini?')">
                                            <i class="fas fa-check"></i> Setujui Tugas
                                        </button>
                                    </form>
                                    <form action="{{ route('pm.tasks.reject', $task) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger w-100"
                                                onclick="return confirm('Yakin ingin menolak tugas ini?')">
                                            <i class="fas fa-times"></i> Tolak Tugas
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
