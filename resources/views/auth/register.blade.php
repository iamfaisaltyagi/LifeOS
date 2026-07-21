<x-guest-layout>
    <h2 class="lo-form-title">Create your LifeOS account</h2>
    <p class="lo-form-subtitle">Set up your workspace and start building better routines.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="lo-field">
            <label for="name" class="lo-label">Full name</label>
            <input id="name" class="lo-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Your name" />
            @error('name')
                <p class="lo-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="lo-field">
            <label for="email" class="lo-label">Email</label>
            <input id="email" class="lo-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@example.com" />
            @error('email')
                <p class="lo-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="lo-field">
            <label for="password" class="lo-label">Password</label>
            <div class="lo-input-wrap">
                <input id="password" class="lo-input lo-input-password" type="password" name="password" required autocomplete="new-password" placeholder="Create password" />
                <button type="button" class="lo-toggle-password" data-toggle-password data-target="password">Show</button>
            </div>
            @error('password')
                <p class="lo-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="lo-field">
            <label for="password_confirmation" class="lo-label">Confirm password</label>
            <div class="lo-input-wrap">
                <input id="password_confirmation" class="lo-input lo-input-password" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat password" />
                <button type="button" class="lo-toggle-password" data-toggle-password data-target="password_confirmation">Show</button>
            </div>
            @error('password_confirmation')
                <p class="lo-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="lo-button lo-button-primary" style="width: 100%; margin-top: 0.35rem;">Create account</button>

        <p class="lo-auth-footer">
            Already registered?
            <a href="{{ route('login') }}">Login here</a>
        </p>
    </form>
</x-guest-layout>
