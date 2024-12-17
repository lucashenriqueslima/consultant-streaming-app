<?php

namespace App\Jobs;

use App\Models\Candidate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendAuthenticationTokenToCandidateJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Candidate $candidate,
        public string $authenticationToken,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
