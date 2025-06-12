@extends('layouts.app')

@section('title', 'Update Tugas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Update Tugas: {{ $task->title }}</h1>
                    <p class="text-muted mb-0">Proyek: {{ $task->project->name }}</p>
                </div>
                <a href="{{ route('employee.tasks.show', $task) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Tambah Update</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('employee.task-updates.store', $task) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi Update <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="5" required 
                                              placeholder="Jelaskan progress atau update yang telah Anda kerjakan...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status Tugas</label>
                                            <select class="form-select @error('status') is-invalid @enderror" 
                                                    id="status" name="status">
                                                <option value="">Tidak mengubah status</option>
                                                @if($task->status !== 'in_progress')
                                                    <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                                                @endif
                                                @if($task->status !== 'completed')
                                                    <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                                                @endif
                                                @if($task->status !== 'on_hold')
                                                    <option value="on_hold" {{ old('status') === 'on_hold' ? 'selected' : '' }}>Ditunda</option>
                                                @endif
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="hours_worked" class="form-label">Jam Kerja</label>
                                            <input type="number" class="form-control @error('hours_worked') is-invalid @enderror" 
                                                   id="hours_worked" name="hours_worked" value="{{ old('hours_worked') }}" 
                                                   min="0" step="0.5" placeholder="Contoh: 2.5">
                                            @error('hours_worked')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Berapa jam yang Anda kerjakan untuk update ini?</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="attachments" class="form-label">Lampiran</label>
                                    <input type="file" class="form-control @error('attachments') is-invalid @enderror" 
                                           id="attachments" name="attachments[]" multiple>
                                    @error('attachments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Upload file pendukung seperti screenshot, dokumen, atau file lainnya. Maksimal 10MB per file.</div>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('employee.tasks.show', $task) }}" class="btn btn-secondary">Batal</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan Update
                                    </button>
                                </div>
                            </form>
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
                                <strong>Status Saat Ini:</strong>
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
                        </div>
                    </div>
                    
                    @if($task->requirements)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Persyaratan</h6>
                            </div>
                            <div class="card-body">
                                <div class="text-muted">
                                    {!! nl2br(e($task->requirements)) !!}
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($task->updates->count() > 0)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Update Terakhir</h6>
                            </div>
                            <div class="card-body">
                                @php $lastUpdate = $task->updates->sortByDesc('created_at')->first(); @endphp
                                <div class="text-muted">
                                    <small>{{ $lastUpdate->created_at->format('d M Y H:i') }}</small>
                                    <div class="mt-1">{{ Str::limit($lastUpdate->description, 100) }}</div>
                                    @if($lastUpdate->hours_worked)
                                        <small class="text-info">{{ $lastUpdate->hours_worked }} jam</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('attachments').addEventListener('change', function(e) {
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

// Auto-fill hours worked based on time
const startTime = new Date();
document.getElementById('hours_worked').addEventListener('focus', function() {
    if (!this.value) {
        const now = new Date();
        const diffHours = (now - startTime) / (1000 * 60 * 60);
        if (diffHours > 0.1) { // More than 6 minutes
            this.value = Math.round(diffHours * 2) / 2; // Round to nearest 0.5
        }
    }
});
</script>
@endpush
