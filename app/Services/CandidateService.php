<?php

namespace App\Services;

use App\Models\Candidate;

class CandidateService
{

    public function create(array $data): Candidate
    {
        return Candidate::create($data);
    }

    public function update(Candidate $candidate, array $data): Candidate
    {
        $candidate->update($data);

        return $candidate;
    }
}
