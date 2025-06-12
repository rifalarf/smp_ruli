<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function project() { return $this->belongsTo(Project::class); }
public function assignedTo() { return $this->belongsTo(User::class, 'assigned_to_id'); }
public function assignedBy() { return $this->belongsTo(User::class, 'assigned_by_id'); }
public function parentTask() { return $this->belongsTo(Task::class, 'parent_task_id'); }
public function subTasks() { return $this->hasMany(Task::class, 'parent_task_id'); }
public function updates() { return $this->hasMany(TaskUpdate::class); }
public function comments() { return $this->morphMany(Comment::class, 'commentable'); }
public function attachments() { return $this->morphMany(Attachment::class, 'attachable'); }
}
