<form method="POST" action="{{ route('login') }}" class="w-50 container">
    @csrf

    <!-- Email Address -->
    <div class="row mb-3">
        <label for="email" class="col-sm-2 col-form-label">{{ __('Email') }}</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required
                autofocus>
        </div>
    </div>

    <!-- Password -->
    <div class="row mb-3">
        <label for="password" class="col-sm-2 col-form-label">{{ __('Password') }}</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" id="password" name="password" required
                autocomplete="current-password">
        </div>
    </div>

    <!-- Remember Me -->
    <div class="block mt-4">
        <label for="remember_me" class="inline-flex items-center">
            <input id="remember_me" type="checkbox"
                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
            <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
        </label>
    </div>

    <div class="flex items-center justify-end mt-4">
        @if (Route::has('password.request'))
            <a class="underline text-sm" href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
        @endif

        <button type="submit" class="btn btn-primary">{{ __('Log in') }}</button>
    </div>
</form>
