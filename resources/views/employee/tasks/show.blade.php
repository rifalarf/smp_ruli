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
                    @if($task->status !== 'completed')
                        <a href="{{ route('employee.task-updates.store', $task) }}" 
                           class="btn btn-primary"
                           onclick="event.preventDefault(); showUpdateModal();">
                            <i class="fas fa-plus"></i> Tambah Update
                        </a>
                    @endif
                    <a href="{{ route('employee.tasks.index') }}" class="btn btn-secondary">
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
                    
                    <!-- Task Updates -->
                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Update Progress</h5>
                            @if($task->status !== 'completed')
                                <button class="btn btn-sm btn-primary" onclick="showUpdateModal()">
                                    <i class="fas fa-plus"></i> Tambah Update
                                </button>
                            @endif
                        </div>
                        <div class="card-body">
                            @if($task->updates && $task->updates->count() > 0)
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
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                    <h6 class="text-muted">Belum ada update</h6>
                                    <p class="text-muted">Mulai tambahkan update progress untuk tugas ini.</p>
                                    @if($task->status !== 'completed')
                                        <button class="btn btn-primary" onclick="showUpdateModal()">
                                            <i class="fas fa-plus"></i> Tambah Update Pertama
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
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
                                <strong>Project Manager:</strong>
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
                    
                    @if($task->status !== 'completed')
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Aksi Cepat</h6>
                            </div>
                            <div class="card-body">
                                @if($task->status === 'pending')
                                    <form action="{{ route('employee.tasks.update-status', $task) }}" method="POST" class="mb-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="in_progress">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-play"></i> Mulai Kerjakan
                                        </button>
                                    </form>
                                @elseif($task->status === 'in_progress')
                                    <form action="{{ route('employee.tasks.update-status', $task) }}" method="POST" class="mb-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="btn btn-success w-100"
                                                onclick="return confirm('Yakin tugas sudah selesai?')">
                                            <i class="fas fa-check"></i> Tandai Selesai
                                        </button>
                                    </form>
                                    <form action="{{ route('employee.tasks.update-status', $task) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="on_hold">
                                        <button type="submit" class="btn btn-warning w-100">
                                            <i class="fas fa-pause"></i> Tunda
                                        </button>
                                    </form>
                                @elseif($task->status === 'on_hold')
                                    <form action="{{ route('employee.tasks.update-status', $task) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="in_progress">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-play"></i> Lanjutkan
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

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Tambah Update Progress</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('employee.task-updates.store', $task) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal_description" class="form-label">Deskripsi Update <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="modal_description" name="description" rows="4" required 
                                  placeholder="Jelaskan progress atau update yang telah Anda kerjakan..."></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_status" class="form-label">Status Tugas</label>
                                <select class="form-select" id="modal_status" name="status">
                                    <option value="">Tidak mengubah status</option>
                                    @if($task->status !== 'in_progress')
                                        <option value="in_progress">Sedang Dikerjakan</option>
                                    @endif
                                    @if($task->status !== 'completed')
                                        <option value="completed">Selesai</option>
                                    @endif
                                    @if($task->status !== 'on_hold')
                                        <option value="on_hold">Ditunda</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_hours_worked" class="form-label">Jam Kerja</label>
                                <input type="number" class="form-control" id="modal_hours_worked" name="hours_worked" 
                                       min="0" step="0.5" placeholder="Contoh: 2.5">
                                <div class="form-text">Berapa jam yang Anda kerjakan untuk update ini?</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_attachments" class="form-label">Lampiran</label>
                        <input type="file" class="form-control" id="modal_attachments" name="attachments[]" multiple>
                        <div class="form-text">Upload file pendukung. Maksimal 10MB per file.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showUpdateModal() {
    const modal = new bootstrap.Modal(document.getElementById('updateModal'));
    modal.show();
}

document.getElementById('modal_attachments').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const maxSize = 10 * 1024 * 1024; // 10MB
    
    let hasError = false;
    files.forEach(file => {
        if (file.size > maxSize) {
            alert(`File ${file.name} terlalu besar. Maksimal 10MB.`);
            hasError = true;
        }
    });
    
    if (hasError) {
        e.target.value = '';
    }
});
</script>
@endpush