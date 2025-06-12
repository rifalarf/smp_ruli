@extends('layouts.app')

@section('title', 'Kanban Board - ' . $project->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>{{ $project->name }}</h1>
            <p class="text-muted mb-0">
                <span class="badge bg-{{ $project->status === 'Selesai' ? 'success' : ($project->status === 'In Progress' ? 'primary' : 'secondary') }}">
                    {{ $project->status }}
                </span>
                <span class="ms-2">Deadline: {{ $project->deadline_date->format('d M Y') }}</span>
            </p>
        </div>
        <div class="btn-group">
            <a href="{{ route('pm.projects.show', $project) }}" class="btn btn-outline-secondary">
                <i class="fas fa-list me-1"></i> View Tabel
            </a>
            <a href="{{ route('pm.projects.edit', $project) }}" class="btn btn-outline-warning">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                <i class="fas fa-plus me-1"></i> Tambah Task
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Kanban Board -->
    @livewire('project.kanban-board', ['project' => $project])
</div>

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Task Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pm.projects.tasks.store', $project) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Task <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="assigned_to_id" class="form-label">Ditugaskan ke <span class="text-danger">*</span></label>
                                <select class="form-select" id="assigned_to_id" name="assigned_to_id" required>
                                    <option value="">Pilih Karyawan</option>
                                    @foreach($project->members as $member)
                                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="deadline" class="form-label">Deadline <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="deadline" name="deadline" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="Belum Dikerjakan">Belum Dikerjakan</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Selesai">Selesai</option>
                                    <option value="Revisi">Revisi</option>
                                    <option value="Blocked">Blocked</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="parent_task_id" class="form-label">Parent Task</label>
                                <select class="form-select" id="parent_task_id" name="parent_task_id">
                                    <option value="">Tidak ada</option>
                                    @foreach($project->tasks()->whereNull('parent_task_id')->get() as $task)
                                        <option value="{{ $task->id }}">{{ $task->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- SortableJS for drag and drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<!-- Livewire Sortable -->
<script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum deadline to tomorrow
    const deadlineInput = document.getElementById('deadline');
    if (deadlineInput) {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        deadlineInput.min = tomorrow.toISOString().split('T')[0];
    }
});
</script>
@endpush
