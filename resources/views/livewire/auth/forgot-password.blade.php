<div class="container">
    <div class="row">
        <div class="mx-auto my-5">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4 text-center">
                        <h2>{{ __('Forgot Password') }}</h2>
                        <p class="text-muted">{{ __('Enter your email to receive a password reset link') }}</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-info text-center">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form wire:submit="sendPasswordResetLink">
                        <!-- Login ID -->
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input type="text" wire:model="email" id="email"
                                class="form-control @error('email') is-invalid @enderror" autofocus autocomplete="email"
                                placeholder="Enter email, phone or username...">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>



                        <div class="mb-3 d-grid">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>{{ __('Send Reset Link') }}</span>
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
                            {{ __('Return to') }}
                            <a href="{{ route('login') }}" class="text-decoration-none">{{ __('Login') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
