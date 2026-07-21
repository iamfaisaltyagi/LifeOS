<x-guest-layout>
    @if (session('status'))
        <div class="lo-alert">
            {{ session('status') }}
        </div>
    @endif

    <h2 class="lo-form-title">Welcome back</h2>
    <p class="lo-form-subtitle">Login to continue with your LifeOS workspace.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="lo-field">
            <label for="email" class="lo-label">Email</label>
            <input id="email" class="lo-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com" />
            @error('email')
                <p class="lo-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="lo-field">
            <label for="password" class="lo-label">Password</label>
            <div class="lo-input-wrap">
                <input id="password" class="lo-input lo-input-password" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" />
                <button type="button" class="lo-toggle-password" data-toggle-password data-target="password">Show</button>
            </div>
            @error('password')
                <p class="lo-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="lo-inline">
            <label for="remember_me" class="lo-checkbox">
                <input id="remember_me" type="checkbox" name="remember">
                <span>Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="lo-link" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit" class="lo-button lo-button-primary" style="width: 100%; margin-top: 0.35rem;">Log in</button>

        <p class="lo-auth-footer">
            New to LifeOS?
            <a href="{{ route('register') }}">Create an account</a>
        </p>
    </form>
</x-guest-layout>
