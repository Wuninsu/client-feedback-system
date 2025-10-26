<div class="container">
    <div class="row">
        <div class="mx-auto my-5">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <!-- Header -->
                        <div class="text-center">
                            <h2 class="h4 fw-semibold">{{ __('Reset password') }}</h2>
                            <p class="text-muted">{{ __('Please enter your new password below') }}</p>
                        </div>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success text-center">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- Registration Form -->
                        <form wire:submit="resetPassword">
                            <div class="row">
                                <!-- Email -->
                                <div class="form-group mb-2">
                                    <label for="email" class="form-label mb-0">{{ __('Email address') }}</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" wire:model.defer="email" placeholder="email@example.com"
                                        autocomplete="email" />
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="form-group mb-2">
                                    <label for="password" class="form-label mb-0">{{ __('Password') }}</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" wire:model.defer="password" placeholder="{{ __('Password') }}"
                                        autocomplete="new-password" />
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="form-group mb-2">
                                    <label for="password_confirmation"
                                        class="form-label mb-0">{{ __('Confirm password') }}</label>
                                    <input type="password"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        id="password_confirmation" wire:model.defer="password_confirmation"
                                        placeholder="{{ __('Confirm password') }}" autocomplete="new-password" />
                                    @error('password_confirmation')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                            </div>
                            <!-- Submit Button -->
                            <div class="mb-3 d-grid">
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                    <span wire:loading.remove>{{ __('Reset password') }}</span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        {{ __('Attempting Reset...') }}
                                    </span>
                                </button>
                            </div>
                        </form>

                        <!-- Login Link -->
                        <div class="text-center text-muted small">
                            {{ __('Already have an account?') }}
                            <a href="{{ route('login') }}" wire:navigate class="text-decoration-none">
                                {{ __('Log in') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
