<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLES = [
        'Startup',
        'Investor',
        'Service Provider',
        'Company',
        'Attendee',
        'Hackathon Participant',
    ];

    public const MATCH_ROLE_MAP = [
        'Startup' => ['Investor', 'Service Provider', 'Company'],
        'Investor' => ['Startup', 'Company'],
        'Service Provider' => ['Startup', 'Company'],
        'Company' => ['Startup', 'Investor', 'Service Provider'],
        'Attendee' => ['Startup', 'Investor', 'Company'],
        'Hackathon Participant' => ['Startup', 'Company', 'Service Provider'],
    ];

    protected $fillable = [
        'name',
        'email',
        'role',
        'company_name',
        'phone',
        'bio',
        'interests',
        'google_id',
        'is_admin',
        'is_active',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function getInterestsArray(): array
    {
        return array_filter(array_map('trim', explode(',', $this->interests ?? '')));
    }

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function sentCollaborationRequests()
    {
        return $this->hasMany(CollaborationRequest::class, 'sender_id');
    }

    public function receivedCollaborationRequests()
    {
        return $this->hasMany(CollaborationRequest::class, 'receiver_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    public function projectMemberships()
    {
        return $this->belongsToMany(Project::class, 'project_members')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public static function findMatchmakingSuggestions(User $user, int $limit = 6)
    {
        $compatibleRoles = self::MATCH_ROLE_MAP[$user->role] ?? [];
        $interests = $user->getInterestsArray();

        $query = self::query()
            ->where('id', '!=', $user->id)
            ->where('is_active', true);

        if (! empty($compatibleRoles)) {
            $query->whereIn('role', $compatibleRoles);
        }

        if (! empty($interests)) {
            $query->where(function ($q) use ($interests) {
                foreach ($interests as $interest) {
                    $q->orWhere('interests', 'like', '%' . $interest . '%');
                }
            });
        }

        return $query->latest()->limit($limit)->get();
    }
}
