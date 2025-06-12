@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Detail Pengguna</h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            @if($user->profile_photo_path)
                                <img src="{{ Storage::url($user->profile_photo_path) }}" 
                                     alt="Profile Photo" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                                     style="width: 150px; height: 150px;">
                                    <i class="fas fa-user fa-4x text-white"></i>
                                </div>
                            @endif
                            
                            <h4 class="card-title">{{ $user->name }}</h4>
                            <p class="text-muted">{{ $user->username }}</p>
                            
                            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }} mb-2">
                                {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                            
                            <div class="mt-3">
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-warning' : 'btn-success' }}"
                                            onclick="return confirm('Yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} pengguna ini?')">
                                        <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Informasi Pengguna</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Nama Lengkap:</strong>
                                    <p>{{ $user->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Username:</strong>
                                    <p>{{ $user->username }}</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Email:</strong>
                                    <p>{{ $user->email }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Nomor Telepon:</strong>
                                    <p>{{ $user->phone_number ?: '-' }}</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Role:</strong>
                                    <p>
                                        <span class="badge bg-primary">{{ $user->role->name }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Divisi:</strong>
                                    <p>{{ $user->division ?: '-' }}</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Tanggal Bergabung:</strong>
                                    <p>{{ $user->created_at->format('d F Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Terakhir Diupdate:</strong>
                                    <p>{{ $user->updated_at->format('d F Y H:i') }}</p>
                                </div>
                            </div>
                            
                            @if($user->email_verified_at)
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Email Terverifikasi:</strong>
                                        <p>{{ $user->email_verified_at->format('d F Y H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($user->isProjectManager() || $user->isEmployee())
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Statistik</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @if($user->isProjectManager())
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <h4 class="text-primary">{{ $user->managedProjects->count() }}</h4>
                                                <p class="text-muted">Proyek Dikelola</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <h4 class="text-success">{{ $user->createdTasks->count() }}</h4>
                                                <p class="text-muted">Tugas Dibuat</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <h4 class="text-info">{{ $user->reports->count() }}</h4>
                                                <p class="text-muted">Laporan Dibuat</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($user->isEmployee())
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <h4 class="text-primary">{{ $user->projects->count() }}</h4>
                                                <p class="text-muted">Proyek Terlibat</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <h4 class="text-warning">{{ $user->assignedTasks->count() }}</h4>
                                                <p class="text-muted">Tugas Ditugaskan</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <h4 class="text-success">{{ $user->assignedTasks->where('status', 'completed')->count() }}</h4>
                                                <p class="text-muted">Tugas Selesai</p>
                                            </div>
                                        </div>
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
