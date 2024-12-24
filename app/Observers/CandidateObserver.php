<?php

namespace App\Observers;

use App\Enums\Panel;
use App\Models\Candidate;
use App\Jobs\{CreateConsultantIlevaJob, ProcessPuxaCapivaraJob};
use App\Notifications\RegisterSendNotifications;
use App\Services\CandidateService;
use App\Services\Documents\Certificates\GenerateCertificateCandidate;
use Illuminate\Support\Facades\Log;

class CandidateObserver
{
    /**
     * Handle the Candidate "created" event.
     */
    public function created(Candidate $candidate): void
    {
        // code here...
    }

    /**
     * Handle the Candidate "updated" event.
     */
    public function updated(Candidate $candidate): void
    {
        if ($candidate->isDirty('status') && CandidateService::isComplateLessons($candidate->status)) {

            $certificateService = new GenerateCertificateCandidate();
            $certificateService->generateAndSavePdf($candidate, Panel::Candidate);

            dispatch(new CreateConsultantIlevaJob($candidate));
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
