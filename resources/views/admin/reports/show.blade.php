@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Detail Laporan</h1>
                <div class="d-flex gap-2">
                    @if($report->status === 'pending')
                        <form action="{{ route('admin.reports.validate', $report) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-success"
                                    onclick="return confirm('Yakin ingin menyetujui laporan ini?')">
                                <i class="fas fa-check"></i> Setujui
                            </button>
                        </form>
                        <form action="{{ route('admin.reports.validate', $report) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Yakin ingin menolak laporan ini?')">
                                <i class="fas fa-times"></i> Tolak
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">{{ $report->title }}</h5>
                        </div>
                        <div class="card-body">
                            @if($report->description)
                                <div class="mb-4">
                                    <h6>Deskripsi:</h6>
                                    <div class="border rounded p-3 bg-light">
                                        {!! nl2br(e($report->description)) !!}
                                    </div>
                                </div>
                            @endif
                            
                            @if($report->content)
                                <div class="mb-4">
                                    <h6>Konten Laporan:</h6>
                                    <div class="border rounded p-3">
                                        {!! nl2br(e($report->content)) !!}
                                    </div>
                                </div>
                            @endif
                            
                            @if($report->attachments && $report->attachments->count() > 0)
                                <div class="mb-4">
                                    <h6>Lampiran:</h6>
                                    <div class="row">
                                        @foreach($report->attachments as $attachment)
                                            <div class="col-md-6 mb-2">
                                                <div class="border rounded p-2 d-flex align-items-center">
                                                    <i class="fas fa-file me-2"></i>
                                                    <div class="flex-grow-1">
                                                        <div>{{ $attachment->original_name }}</div>
                                                        <small class="text-muted">{{ $attachment->file_size }} bytes</small>
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
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Informasi Laporan</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Status:</strong>
                                <div class="mt-1">
                                    @if($report->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($report->status === 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($report->status === 'rejected')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </div>
                            </div>
                            
                            @if($report->type)
                                <div class="mb-3">
                                    <strong>Tipe:</strong>
                                    <div class="mt-1">{{ ucfirst($report->type) }}</div>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <strong>Proyek:</strong>
                                <div class="mt-1">
                                    <a href="{{ route('pm.projects.show', $report->project) }}" 
                                       class="text-decoration-none">
                                        {{ $report->project->name }}
                                    </a>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Dibuat Oleh:</strong>
                                <div class="mt-1 d-flex align-items-center">
                                    @if($report->user->profile_photo_path)
                                        <img src="{{ Storage::url($report->user->profile_photo_path) }}" 
                                             alt="{{ $report->user->name }}" 
                                             class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                             style="width: 32px; height: 32px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div>{{ $report->user->name }}</div>
                                        <small class="text-muted">{{ $report->user->role->name }}</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Tanggal Dibuat:</strong>
                                <div class="mt-1">{{ $report->created_at->format('d F Y H:i') }}</div>
                            </div>
                            
                            @if($report->validated_at)
                                <div class="mb-3">
                                    <strong>Tanggal Validasi:</strong>
                                    <div class="mt-1">{{ $report->validated_at->format('d F Y H:i') }}</div>
                                </div>
                            @endif
                            
                            @if($report->validated_by)
                                <div class="mb-3">
                                    <strong>Divalidasi Oleh:</strong>
                                    <div class="mt-1">{{ $report->validatedBy->name }}</div>
                                </div>
                            @endif
                            
                            @if($report->validation_notes)
                                <div class="mb-3">
                                    <strong>Catatan Validasi:</strong>
                                    <div class="mt-1 p-2 border rounded bg-light">
                                        {!! nl2br(e($report->validation_notes)) !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($report->status === 'pending')
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Validasi dengan Catatan</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.reports.validate', $report) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                id="status" name="status" required>
                                            <option value="">Pilih Status</option>
                                            <option value="approved">Setujui</option>
                                            <option value="rejected">Tolak</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="validation_notes" class="form-label">Catatan Validasi</label>
                                        <textarea class="form-control @error('validation_notes') is-invalid @enderror" 
                                                  id="validation_notes" name="validation_notes" rows="3" 
                                                  placeholder="Berikan catatan untuk keputusan validasi...">{{ old('validation_notes') }}</textarea>
                                        @error('validation_notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-check"></i> Proses Validasi
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
