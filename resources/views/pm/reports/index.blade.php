@extends('layouts.app')

@section('title', 'Laporan Proyek')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Laporan Proyek</h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('pm.reports.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Buat Laporan
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <ul class="dropdown-menu">
                            <li><h6 class="dropdown-header">Status</h6></li>
                            <li><a class="dropdown-item" href="{{ route('pm.reports.index') }}">Semua</a></li>
                            <li><a class="dropdown-item" href="{{ route('pm.reports.index', ['status' => 'pending']) }}">Pending</a></li>
                            <li><a class="dropdown-item" href="{{ route('pm.reports.index', ['status' => 'approved']) }}">Disetujui</a></li>
                            <li><a class="dropdown-item" href="{{ route('pm.reports.index', ['status' => 'rejected']) }}">Ditolak</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">Tipe</h6></li>
                            <li><a class="dropdown-item" href="{{ route('pm.reports.index', ['type' => 'progress']) }}">Progress</a></li>
                            <li><a class="dropdown-item" href="{{ route('pm.reports.index', ['type' => 'weekly']) }}">Mingguan</a></li>
                            <li><a class="dropdown-item" href="{{ route('pm.reports.index', ['type' => 'monthly']) }}">Bulanan</a></li>
                            <li><a class="dropdown-item" href="{{ route('pm.reports.index', ['type' => 'final']) }}">Final</a></li>
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
                                        <th>Tipe</th>
                                        <th>Status</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                        <tr>
                                            <td>
                                                <strong>{{ $report->title }}</strong>
                                                @if($report->description)
                                                    <br><small class="text-muted">{{ Str::limit($report->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('pm.projects.show', $report->project) }}" 
                                                   class="text-decoration-none">
                                                    {{ $report->project->name }}
                                                </a>
                                            </td>
                                            <td>
                                                @if($report->type)
                                                    <span class="badge bg-secondary">{{ ucfirst($report->type) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
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
                                                {{ $report->created_at->format('d M Y') }}
                                                <br><small class="text-muted">{{ $report->created_at->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('pm.reports.show', $report) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($report->status === 'pending')
                                                        <a href="{{ route('pm.reports.edit', $report) }}" 
                                                           class="btn btn-sm btn-outline-warning">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
                                                    @if($report->status !== 'approved')
                                                        <form action="{{ route('pm.reports.destroy', $report) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                    onclick="return confirm('Yakin ingin menghapus laporan ini?')">
                                                                <i class="fas fa-trash"></i>
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
                            <h5 class="text-muted">Belum ada laporan</h5>
                            <p class="text-muted">Mulai buat laporan untuk proyek yang Anda kelola.</p>
                            <a href="{{ route('pm.reports.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Buat Laporan Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
