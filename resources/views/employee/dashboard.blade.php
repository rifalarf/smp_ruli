@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('header')
    <h1 class="h2">Dashboard Karyawan</h1>
    <p class="text-muted">Selamat datang kembali, {{ Auth::user()->name }}!</p>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Tugas Dikerjakan
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['in_progress'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-secondary border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-secondary text-uppercase mb-1">
                                Menunggu Dikerjakan
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pause-circle fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-warning border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Perlu Revisi
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['revision'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Tugas Selesai
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['completed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Tugas --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title mb-0">
                <i class="fas fa-tasks me-2"></i>Daftar Tugas Saya
            </h3>
        </div>
        <div class="card-body p-0">
            @if($tasks->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h4>Anda tidak memiliki tugas saat ini.</h4>
                    <p class="text-muted">Nikmati waktu luang Anda!</p>
                </div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($tasks as $task)
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{ $task->title }}</h5>
                                <small class="text-muted">
                                    @if($task->isOverdue())
                                        <span class="text-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Terlambat {{ $task->deadline->diffForHumans() }}
                                        </span>
                                    @else
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        Deadline: {{ $task->deadline->format('d M Y') }}
                                    @endif
                                </small>
                            </div>
                            <p class="mb-1 text-muted">
                                Proyek: <a href="#">{{ $task->project->name }}</a>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge rounded-pill {{ $task->status_badge_class }}">
                                        {{ $task->status }}
                                    </span>
                                    <span class="badge rounded-pill bg-info text-dark">
                                        Prioritas: {{ $task->priority ?? 'Normal' }}
                                    </span>
                                </div>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection