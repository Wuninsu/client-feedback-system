<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Login extends Component
{

    public string $password = '';
    public bool $remember = false;
    public $login_id;

    #[Title('Login')]
    public function login(): void
    {
        $fieldType = $this->detectFieldType($this->login_id);

        $this->validate([
            'login_id' => ['required', "exists:users,{$fieldType}"],
            'password' => ['required', 'min:8'],
        ], [
            'login_id.required' => 'Login Id is required.',
            'login_id.exists' => ucfirst($fieldType) . ' is not registered.',
            'password.required' => 'Password is required.',
        ]);

        $this->ensureIsNotRateLimited();

        if (!Auth::attempt([$fieldType => $this->login_id, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());
            logActivity('login_failed');

            throw ValidationException::withMessages([
                'login_id' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();
        logActivity('login');
        $this->redirectIntended(route('dashboard'));
    }

    protected function detectFieldType($value): string
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }

        if (preg_match('/^\d{10,15}$/', $value)) {
            return 'phone'; // Adjust regex based on phone format
        }

        return 'username';
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login_id' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->login_id) . '|' . request()->ip());
    }
}
