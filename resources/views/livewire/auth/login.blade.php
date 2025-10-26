<div class="container">
    <div class="row">
        <div class="mx-auto my-5">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4 text-center">
                        <h2>{{ __('Login') }}</h2>
                        <p class="text-muted">{{ __('Enter your email and password below to log in') }}</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-info text-center">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form wire:submit="login">
                        <!-- Login ID -->
                        <div class="mb-3">
                            <label for="login_id" class="form-label">{{ __('Login Id') }}</label>
                            <input type="text" wire:model="login_id" id="email"
                                class="form-control @error('login_id') is-invalid @enderror" autofocus
                                autocomplete="login_id" placeholder="Enter email, phone or username...">
                            @error('login_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3 position-relative">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input type="password" wire:model="password" id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                autocomplete="current-password" placeholder="{{ __('Password') }}">
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="position-absolute top-0 end-0 mt-1 me-2 small text-decoration-none">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>

                        <!-- Remember Me -->
                        <div class="form-check mb-3">
                            <input type="checkbox" wire:model="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
                        </div>
                        <div class="mb-3 d-grid">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>{{ __('Log in') }}</span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                    {{ __('Authenticating...') }}
                                </span>
                            </button>
                        </div>
                    </form>

                    @if (Route::has('register'))
                        <div class="text-center text-muted small">
                            {{ __("Don't have an account?") }}
                            <a href="{{ route('register') }}">{{ __('Register') }}</a>
                            {{ __(' or ') }}
                            <a href="{{ route('home') }}"
                                class="small">
                                {{ __('Go home') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
