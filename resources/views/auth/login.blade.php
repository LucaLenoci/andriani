<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success mb-4">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold text-dark">{{ __('Email') }}</label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   required
                   autofocus
                   autocomplete="username">
            @error('email')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold text-dark">{{ __('Password') }}</label>
            <input id="password"
                   type="password"
                   name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required
                   autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
            <label class="form-check-label text-dark" for="remember_me">
                {{ __('Rimani connesso') }}
            </label>
        </div>

        <!-- Login & Forgot Password -->
		<div class="d-flex flex-column align-items-center">
			<button type="submit" class="btn btn-primary mb-2">
				{{ __('Log in') }}
			</button>
			@if (Route::has('password.request'))
				<a class="text-decoration-underline text-muted small" href="{{ route('password.request') }}">
					{{ __('Password dimenticata?') }}
				</a>
			@endif
		</div>
    </form>
</x-guest-layout>
