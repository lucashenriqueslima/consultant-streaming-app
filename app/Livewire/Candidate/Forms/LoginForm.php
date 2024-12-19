<?php

namespace App\Livewire\Candidate\Forms;

use App\Enums\Association;
use App\Enums\CandidateStatus;
use App\Jobs\CreateAccessLogJob;
use App\Jobs\SendAuthenticationTokenToCandidateJob;
use App\Models\Candidate;
use App\Models\Ileva\ConsultantIleva;
use App\Services\CandidateService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Octane\Facades\Octane;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{

    #[Validate('required|string|in:solidy,nova,motoclub')]
    public string $association = 'solidy';

    #[Validate('required|string|max:14')]
    public string $cpf = '';

    #[Validate('required|string|max:7')]
    public string $authenticationToken = '';

    public bool $showAuthenticationTokenInput = false;


    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.cpf' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->cpf) . '|' . request()->ip());
    }

    private function generateAuthenticationToken(): string
    {
        $rawAuthenticationToken = (string) rand(1000, 999999);
        //put '-' in the middle of the string
        return substr($rawAuthenticationToken, 0, 3) . '-' . substr($rawAuthenticationToken, 3);
    }

    public function handleAuthenticationToken(CandidateService $candidateService): void
    {
        $this->ensureIsNotRateLimited();

        try {
            $candidate = Candidate::where('cpf', $this->cpf)
                ->where('association', $this->association)
                // ->whereNotIn('status', [CandidateStatus::REFUSED_BY_CRIMINAL_HISTORY->value, CandidateStatus::REFUSED_ON_TEST->value])
                ->firstOrFail();

            $generatedAuthenticationToken = $this->generateAuthenticationToken();

            dispatch(
                new SendAuthenticationTokenToCandidateJob(
                    $candidate,
                    $generatedAuthenticationToken,
                )
            );

            $candidateService->update($candidate, [
                'authentication_token' => $generatedAuthenticationToken,
                'token_expires_at' => now()->addMinutes(10)
            ]);


            RateLimiter::clear($this->throttleKey());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.cpf' => trans('auth.cpf_not_found'),
            ]);
        }
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        try {
            $candidate = Candidate::where('cpf', $this->cpf)
                ->where('association', $this->association)
                ->firstOrFail();

            if (CandidateStatus::isNotOneOf([CandidateStatus::ACTIVE, CandidateStatus::COMPLETED_LESSONS, CandidateStatus::ACCEPTED], $candidate->status->value)) {
                throw ValidationException::withMessages([
                    'status' => CandidateStatus::getStatusMessage($candidate->status->value),
                ]);
            }

            Auth::guard('candidate')->loginUsingId($candidate->id, true);

            RateLimiter::clear($this->throttleKey());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.cpf' => trans('auth.failed'),
            ]);
        }
    }
}
