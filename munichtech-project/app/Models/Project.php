<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'title',
        'description',
        'status',
        'progress',
        'tags',
    ];

    protected function casts(): array
    {
        return [
            'progress' => 'integer',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Relación directa con los usuarios colaboradores a través de la tabla intermedia
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function milestones()
    {
        return $this->hasMany(ProjectMilestone::class);
    }

    public function tasks()
    {
        return $this->hasMany(ProjectTask::class);
    }

    // ── Helpers ────────────────────────────────────────────────────

    public function getTags(): array
    {
        return array_filter(array_map('trim', explode(',', $this->tags ?? '')));
    }

    public function hasUser(User $user): bool
    {
        return $this->owner_id === $user->id || $this->members()->where('user_id', $user->id)->exists();
    }

    public function getMemberRole(User $user): ?string
    {
        if ($this->owner_id === $user->id) {
            return 'owner';
        }

        return $this->members()->where('user_id', $user->id)->first()?->pivot?->role;
    }

    public function canEdit(User $user): bool
    {
        $role = $this->getMemberRole($user);
        return in_array($role, ['owner', 'lead']);
    }
}
