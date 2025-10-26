<div class="container">
    <div class="row">
        <div class="mx-auto my-5">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <!-- Header -->
                        <div class="text-center">
                            <h2 class="h4 fw-semibold">{{ __('Register') }}</h2>
                            <p class="text-muted">{{ __('Enter your details below to create your account') }}</p>
                        </div>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success text-center">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- Registration Form -->
                        <form wire:submit.prevent="register">
                            <div class="row">
                                <!-- Name -->
                                <div class="form-group mb-2">
                                    <label for="username" class="form-label mb-0">{{ __('Username') }}</label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                                        id="username" wire:model.defer="username" placeholder="{{ __('User name') }}"
                                        autocomplete="username" autofocus />
                                    @error('username')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

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
                                    <span wire:loading.remove>{{ __('Create Account') }}</span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        {{ __('Attempting Create...') }}
                                    </span>
                                </button>
                            </div>
                        </form>

                        <!-- Login Link -->
                        <div class="text-center text-muted small">
                            {{ __('Already have an account?') }}
                            <a href="{{ route('login') }}" wire:navigate class="">
                                {{ __('Login') }}
                            </a>
                            {{ __(' or ') }}
                            <a href="{{ route('home') }}" class="small">
                                {{ __('Go home') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
