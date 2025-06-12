<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function projectManager() { return $this->belongsTo(User::class, 'project_manager_id'); }
public function members() { return $this->belongsToMany(User::class)->withPivot('project_role')->withTimestamps(); }
public function tasks() { return $this->hasMany(Task::class); }
    
}
