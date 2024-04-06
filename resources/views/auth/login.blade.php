@extends("layouts.site", ["metatitle" => "Log In"])
@section("body")
    <h1>Log in</h1>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <x-input-label for="name" :value="__('Username')" />
            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="form-control"
                            type="password"
                            name="password"
                            required autocomplete="password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <!-- Remember Me -->
            <div class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
            </div>
        </div>

        <div class="form-group">
            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
        

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">
                {{ __('Forgot password') }}
            </a>
            <br>
            <a href="{{ route('register') }}">
                {{ __('Register') }}
            </a>
        @endif
    </form>
@endsection