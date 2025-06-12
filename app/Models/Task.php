<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'assigned_to_id',
        'assigned_by_id',
        'status',
        'deadline',
        'parent_task_id'
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    // Relationships
    public function project() 
    { 
        return $this->belongsTo(Project::class); 
    }
    
    public function assignedTo() 
    { 
        return $this->belongsTo(User::class, 'assigned_to_id'); 
    }
    
    public function assignedBy() 
    { 
        return $this->belongsTo(User::class, 'assigned_by_id'); 
    }
    
    public function parentTask() 
    { 
        return $this->belongsTo(Task::class, 'parent_task_id'); 
    }
    
    public function subTasks() 
    { 
        return $this->hasMany(Task::class, 'parent_task_id'); 
    }
    
    public function updates() 
    { 
        return $this->hasMany(TaskUpdate::class); 
    }
    
    public function comments() 
    { 
        return $this->morphMany(Comment::class, 'commentable'); 
    }
    
    public function attachments() 
    { 
        return $this->morphMany(Attachment::class, 'attachable'); 
    }

    // Helper methods
    public function getDaysRemainingAttribute()
    {
        return now()->diffInDays($this->deadline, false);
    }

    public function isOverdue()
    {
        return $this->deadline < now() && $this->status !== 'Selesai';
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'Belum Dikerjakan' => 'bg-secondary',
            'In Progress' => 'bg-warning',
            'Selesai' => 'bg-success',
            'Revisi' => 'bg-info',
            'Blocked' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    public function getTotalHoursSpentAttribute()
    {
        return $this->updates()->sum('hours_spent') ?? 0;
    }
}
