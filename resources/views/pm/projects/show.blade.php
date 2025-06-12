@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>{{ $project->name }}</h1>
            <p class="text-muted mb-0">{{ $project->description }}</p>
        </div>
        <a href="{{ route('pm.projects.edit', $project) }}" class="btn btn-outline-primary">Edit Proyek</a>
    </div>

    <hr>

    <h3 class="mb-3">Papan Kanban</h3>
    @livewire('project.kanban-board', ['project' => $project], key($project->id))
@endsection

@push('scripts')
    {{-- Ini diperlukan agar Livewire Sortable bisa berfungsi --}}
    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>
@endpush