@extends('layouts.app')

@section('title', 'Validasi Laporan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Validasi Laporan</h1>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter"></i> Filter Status
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.reports.index') }}">Semua</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.reports.index', ['status' => 'pending']) }}">Pending</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.reports.index', ['status' => 'approved']) }}">Disetujui</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.reports.index', ['status' => 'rejected']) }}">Ditolak</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    @if($reports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Proyek</th>
                                        <th>Dibuat Oleh</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                        <tr>
                                            <td>
                                                <strong>{{ $report->title }}</strong>
                                                @if($report->type)
                                                    <br><small class="text-muted">{{ ucfirst($report->type) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('pm.projects.show', $report->project) }}" 
                                                   class="text-decoration-none">
                                                    {{ $report->project->name }}
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
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
                                            </td>
                                            <td>
                                                {{ $report->created_at->format('d M Y') }}
                                                <br><small class="text-muted">{{ $report->created_at->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                @if($report->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($report->status === 'approved')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @elseif($report->status === 'rejected')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.reports.show', $report) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($report->status === 'pending')
                                                        <form action="{{ route('admin.reports.validate', $report) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="approved">
                                                            <button type="submit" class="btn btn-sm btn-success"
                                                                    onclick="return confirm('Yakin ingin menyetujui laporan ini?')">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.reports.validate', $report) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="rejected">
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                    onclick="return confirm('Yakin ingin menolak laporan ini?')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $reports->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada laporan</h5>
                            <p class="text-muted">Belum ada laporan yang perlu divalidasi.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
