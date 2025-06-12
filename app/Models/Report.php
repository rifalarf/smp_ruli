<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'submitted_by_id',
        'content',
        'status',
        'validation_notes'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by_id');
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'Menunggu Persetujuan' => 'bg-warning',
            'Disetujui' => 'bg-success',
            'Ditolak' => 'bg-danger',
            default => 'bg-secondary'
        };
    }
}
