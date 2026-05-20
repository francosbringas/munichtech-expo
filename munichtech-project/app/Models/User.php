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

    public const ROLES = [
        'Startup',
        'Investor',
        'Service Provider',
        'Company',
        'Attendee',
        'Hackathon Participant',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'company_name',
        'phone',
        'bio',
        'is_admin',
        'password',
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
            'is_admin' => 'boolean',
        ];
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

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }
}
