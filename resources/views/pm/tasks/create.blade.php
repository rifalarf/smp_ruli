@extends('layouts.app')

@section('title', 'Tambah Tugas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Tambah Tugas - {{ $project->name }}</h1>
                <a href="{{ route('pm.projects.show', $project) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Proyek
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('pm.projects.tasks.store', $project) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="5">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="requirements" class="form-label">Persyaratan</label>
                                    <textarea class="form-control @error('requirements') is-invalid @enderror" 
                                              id="requirements" name="requirements" rows="3" 
                                              placeholder="Jelaskan persyaratan atau kriteria penyelesaian tugas...">{{ old('requirements') }}</textarea>
                                    @error('requirements')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="assigned_to" class="form-label">Ditugaskan Kepada</label>
                                    <select class="form-select @error('assigned_to') is-invalid @enderror" 
                                            id="assigned_to" name="assigned_to">
                                        <option value="">Pilih Anggota Tim</option>
                                        @foreach($project->members as $member)
                                            <option value="{{ $member->id }}" {{ old('assigned_to') == $member->id ? 'selected' : '' }}>
                                                {{ $member->name }} ({{ $member->role->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('assigned_to')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Prioritas <span class="text-danger">*</span></label>
                                    <select class="form-select @error('priority') is-invalid @enderror" 
                                            id="priority" name="priority" required>
                                        <option value="">Pilih Prioritas</option>
                                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Rendah</option>
                                        <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Sedang</option>
                                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>Tinggi</option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                                        <option value="on_hold" {{ old('status') === 'on_hold' ? 'selected' : '' }}>Ditunda</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" name="start_date" value="{{ old('start_date') }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Tenggat Waktu</label>
                                    <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                           id="due_date" name="due_date" value="{{ old('due_date') }}">
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="estimated_hours" class="form-label">Estimasi Jam Kerja</label>
                                    <input type="number" class="form-control @error('estimated_hours') is-invalid @enderror" 
                                           id="estimated_hours" name="estimated_hours" value="{{ old('estimated_hours') }}" 
                                           min="0" step="0.5">
                                    @error('estimated_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="attachments" class="form-label">Lampiran</label>
                                    <input type="file" class="form-control @error('attachments') is-invalid @enderror" 
                                           id="attachments" name="attachments[]" multiple>
                                    @error('attachments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Anda dapat memilih beberapa file sekaligus. Maksimal 10MB per file.</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('pm.projects.show', $project) }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Tugas
                            </button>
                        </div>
                    </form>
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

// Set minimum date to today
document.getElementById('start_date').min = new Date().toISOString().split('T')[0];
document.getElementById('due_date').min = new Date().toISOString().split('T')[0];
</script>
@endpush
