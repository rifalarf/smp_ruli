<div class="row" wire:sortable-group="onStatusUpdate">
    @foreach(['Belum Dikerjakan', 'In Progress', 'Selesai', 'Revisi', 'Blocked'] as $status)
        <div class="col" wire:key="group-{{ $status }}">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">{{ $status }}</div>
                <div class="card-body bg-light" wire:sortable-group.item-group="{{ $status }}">
                    @if(isset($tasksByStatus[$status]))
                        @foreach($tasksByStatus[$status] as $task)
                            <div class="card mb-2" wire:key="task-{{ $task->id }}" wire:sortable-group.item="{{ $task->id }}">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $task->title }}</h6>
                                    <p class="card-subtitle mb-2 text-muted small">
                                        {{ $task->assignedTo->name ?? 'Belum ada' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>