@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Dashboard Karyawan</h1>

    <div class="alert alert-info">
        Selamat datang, {{ Auth::user()->name }}! Anda memiliki {{ $in_progress_tasks_count }} tugas yang sedang dikerjakan.
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h3>Daftar Tugas Saya</h3>
        </div>
        <div class="card-body">
            @if($tasks->isEmpty())
                <div class="alert alert-success">Anda tidak memiliki tugas saat ini. Selamat beristirahat!</div>
            @else
                <div class="list-group">
                    @foreach($tasks as $task)
                        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">{{ $task->title }}</h5>
                                <p class="mb-1 text-muted">Proyek: {{ $task->project->name }}</p>
                                <small>Deadline: {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}</small>
                            </div>
                            <span class="badge rounded-pill 
                                @switch($task->status)
                                    @case('In Progress') text-bg-primary @break
                                    @case('Selesai') text-bg-success @break
                                    @case('Revisi') text-bg-warning @break
                                    @case('Blocked') text-bg-danger @break
                                    @default text-bg-secondary
                                @endswitch">
                                {{ $task->status }}
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection