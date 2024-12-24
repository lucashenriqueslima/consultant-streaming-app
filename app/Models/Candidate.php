<?php

namespace App\Models;

use App\Enums\CandidateStatus;
use App\Observers\CandidateObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;


#[ObservedBy([CandidateObserver::class])]
class Candidate extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

    protected $casts = [
        'status' => CandidateStatus::class,
        'date_of_birth' => 'date',
        'token_expires_at' => 'datetime',
        'date_of_the_test' => 'datetime',
    ];

    protected $hidden = [
        'remember_token',
    ];

    public function progress(): HasMany
    {
        return $this->hasMany(CandidateProgress::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(CandidateCertificate::class);
    }

}
