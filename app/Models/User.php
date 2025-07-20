<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are not mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [
        'id',
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
        ];
    }

    protected $appends = [
        'disabled',
        'email_verified',
    ];

    /**
     * Get if the user id disabled or not
     */
    public function disabled(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->trashed(),
        );

    }

    /**
     * Get if the user id disabled/ temporary suspended or not
     */
    public function emailVerified(): Attribute
    {
        return Attribute::make(
            get: fn () => filled($this->email_verified_at),
        );

    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }
}
