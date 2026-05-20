<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public const STATUS_PLANNING = 'planning';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_PAUSED = 'paused';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'owner_id',
        'collaboration_request_id',
        'title',
        'description',
        'progress',
        'status',
        'company_name',
    ];

    protected $casts = [
        'progress' => 'integer',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function collaborationRequest()
    {
        return $this->belongsTo(CollaborationRequest::class);
    }

    public function milestones()
    {
        return $this->hasMany(ProjectMilestone::class);
    }
}
