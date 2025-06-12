@extends('layouts.app')

@section('content')
    <h1>{{ $project->name }}</h1>
    <p>{{ $project->description }}</p>

    <hr>

    <h3>Papan Kanban</h3>
    @livewire('project.kanban-board', ['project' => $project])

@endsection

@push('scripts')
    {{-- Ini diperlukan agar Livewire Sortable bisa berfungsi --}}
    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>
@endpush