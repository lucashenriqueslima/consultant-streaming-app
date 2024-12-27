<?php

namespace App\Services;

use App\Models\Candidate;
use App\Enums\CandidateStatus;

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

    public static function isNotOneOf(array $statuses, string $status): bool
    {
        $statusValues = array_map(fn($statusObject) => $statusObject->value, $statuses);
        return !in_array($status, $statusValues);
    }

    public static function isPendingRegistration(CandidateStatus $status): bool
    {
        return $status == CandidateStatus::PENDING_REGISTRATION;
    }

    public static function isComplateLessons(CandidateStatus $status): bool
    {
        return $status == CandidateStatus::COMPLETED_LESSONS;
    }
}
