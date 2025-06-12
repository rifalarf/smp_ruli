<?php


namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role_id',
        'division',
        'phone_number',
        'profile_photo_path',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'project_manager_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class)->withPivot('project_role')->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to_id');
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_by_id');
    }

    public function taskUpdates()
    {
        return $this->hasMany(TaskUpdate::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function submittedReports()
    {
        return $this->hasMany(Report::class, 'submitted_by_id');
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role->slug === 'admin';
    }

    public function isProjectManager()
    {
        return $this->role->slug === 'pm';
    }

    public function isEmployee()
    {
        return $this->role->slug === 'employee';
    }
}
