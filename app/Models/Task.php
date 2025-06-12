<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'deadline',
        'assigned_to_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function updates()
    {
        return $this->hasMany(TaskUpdate::class);
    }

    /**
     * Accessor untuk mendapatkan kelas CSS badge berdasarkan status.
     *
     * @return string
     */
    public function getStatusBadgeClassAttribute()
    {
        switch ($this->status) {
            case 'In Progress':
                return 'bg-primary';
            case 'Selesai':
                return 'bg-success';
            case 'Revisi':
                return 'bg-warning text-dark';
            case 'Blocked':
                return 'bg-danger';
            case 'Belum Dikerjakan':
            default:
                return 'bg-secondary';
        }
    }

    /**
     * Memeriksa apakah tugas sudah melewati deadline.
     *
     * @return bool
     */
    public function isOverdue()
    {
        return $this->deadline && $this->deadline->isPast() && $this->status !== 'Selesai';
    }
}