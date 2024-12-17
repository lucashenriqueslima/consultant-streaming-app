<?php

namespace App\Observers;

use App\DTOs\IlevaConsultantDTO;
use App\Enums\CandidateStatus;
use App\Models\Candidate;
use App\Services\Ileva\RegisterConsultant;

class CandidateObserver
{
    /**
     * Handle the Candidate "created" event.
     */
    public function created(Candidate $candidate): void
    {
        //
    }

    /**
     * Handle the Candidate "updated" event.
     */
    public function updated(Candidate $candidate): void
    {
        $registerConsultant = new RegisterConsultant();
        if ($candidate->isDirty('status') && $candidate->status == CandidateStatus::ACCEPTED) {
            $registerConsultant->execute(
                new IlevaConsultantDTO(
                    status: $candidate->status->value,
                    name: $candidate->name,
                    email: $candidate->email,
                    phone: $candidate->phone,
                    cpf: $candidate->cpf,
                    team_code: $candidate->ileva_team_id,
                    association: 'solidy',
                )
            );
        }
    }

    /**
     * Handle the Candidate "deleted" event.
     */
    public function deleted(Candidate $candidate): void
    {
        //
    }

    /**
     * Handle the Candidate "restored" event.
     */
    public function restored(Candidate $candidate): void
    {
        //
    }

    /**
     * Handle the Candidate "force deleted" event.
     */
    public function forceDeleted(Candidate $candidate): void
    {
        //
    }
}
