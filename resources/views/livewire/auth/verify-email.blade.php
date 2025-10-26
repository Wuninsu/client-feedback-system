{{-- <div class="mt-4 flex flex-col gap-6">
    <flux:text class="text-center">
        {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
    </flux:text>

    @if (session('status') == 'verification-link-sent')
        <flux:text class="text-center font-medium !dark:text-green-400 !text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </flux:text>
    @endif

    <div class="flex flex-col items-center justify-between space-y-3">
        <flux:button wire:click="sendVerification" variant="primary" class="w-full">
            {{ __('Resend verification email') }}
        </flux:button>

        <flux:link class="text-sm cursor-pointer" wire:click="logout">
            {{ __('Log out') }}
        </flux:link>
    </div>
</div> --}}

<div>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="mb-4 text-center">
                    {{-- <h2>{{ __('Verify Email') }}</h2> --}}
                    <p class="text-muted">
                        {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
                    </p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <flux:text class="text-center font-medium !dark:text-green-400 !text-green-600">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </flux:text>
                @endif

                <div class="mb-3 d-grid">
                    <button type="submit" class="btn btn-primary" wire:click="sendVerification"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove> {{ __('Resend verification email') }}</span>
                        <span wire:loading>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            {{ __('Resending...') }}
                        </span>
                    </button>
                </div>

                @if (Route::has('register'))
                    <div class="text-center text-muted small">
                        <a class="text-sm" wire:click="logout">
                            {{ __('Log out') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
