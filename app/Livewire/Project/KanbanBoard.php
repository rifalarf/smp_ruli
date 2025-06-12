<?php
namespace App\Livewire\Project;

use Livewire\Component;
use App\Models\Project;
use App\Models\Task;

class KanbanBoard extends Component
{
    public Project $project;
    public $tasksByStatus;

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->loadTasks();
    }

    public function loadTasks()
    {
        $this->tasksByStatus = $this->project->tasks()
            ->with('assignedTo')
            ->get()
            ->groupBy('status');
    }

    public function onStatusUpdate($taskId, $newStatus)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->status = $newStatus;
            $task->save();
            $this->loadTasks(); // Muat ulang tugas setelah update
        }
    }

    public function render()
    {
        return view('livewire.project.kanban-board');
    }
}