@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manajemen Proyek</h1>
        <a href="{{ route('pm.projects.create') }}" class="btn btn-primary">Buat Proyek Baru</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Proyek</th>
                            <th>Status</th>
                            <th>Prioritas</th>
                            <th>Deadline</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td>{{ $project->name }}</td>
                                <td><span class="badge rounded-pill text-bg-primary">{{ $project->status }}</span></td>
                                <td>{{ $project->priority }}</td>
                                <td>{{ \Carbon\Carbon::parse($project->deadline_date)->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('pm.projects.show', $project) }}" class="btn btn-sm btn-info">Lihat</a>
                                    <a href="{{ route('pm.projects.edit', $project) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('pm.projects.destroy', $project) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Anda belum memiliki proyek.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $projects->links() }}
            </div>
        </div>
    </div>
@endsection