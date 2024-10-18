<?php

namespace App\Livewire\Forms;

use App\Enums\Association;
use App\Jobs\CreateAccessLogJob;
use App\Models\IlevaSolidy\ConsultantIlevaSolidy;
use App\Models\User;
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
    #[Validate('required|string|in:solidy,nova')]
    public string $association = 'solidy';

    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|numeric|digits:4')]
    public string $firstFourCpfNumbers = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $mutatedFirstFourCpfNumbers = Str::substrReplace($this->firstFourCpfNumbers, '.', 3, 0);

        try {

            $databaseConnection = Association::from($this->association)->getDatabaseConnection();

            $consultant = ConsultantIlevaSolidy::on($databaseConnection)
                ->select('nome', 'email', 'cpf')
                ->where('email', $this->email)
                ->where('cpf', 'like', "{$mutatedFirstFourCpfNumbers}%")->firstOrFail();

            $user = $this->updateOrCreateUser($consultant, $this->association);

            Auth::loginUsingId($user->id, $this->remember);

            dispatch(
                new CreateAccessLogJob(
                    $user,
                    request()->ip()
                )
            );



            RateLimiter::clear($this->throttleKey());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.email' => trans('auth.failed'),
            ]);
        }
    }


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
            'form.email' => trans('auth.throttle', [
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
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }

    private function updateOrCreateUser(ConsultantIlevaSolidy $consultant, string $association): User
    {
        return User::updateOrCreate(
            [
                'email' => $consultant->email,
                'association' => $association,
            ],
            [
                'name' => $consultant->nome,
                'email' => $consultant->email,
                'password' => bcrypt($consultant->cpf),
                'association' => $association,
            ]
        );
    }
}
