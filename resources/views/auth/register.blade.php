@extends("layouts.site", ["metatitle" => "Register"])
@section("body")
    <x-spacer/>
    <h1>Sign up for an account</h1>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Username -->
        <div class="form-group">
            <x-input-label for="name" :value="__('Username')" />
            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="form-control"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="form-control"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Invite Code -->
        @if(!$firstuser)
        <div class="form-group">
            <x-input-label for="invite_code" :value="__('Invite Code')" />

            <x-text-input id="invite_code" class="form-control"
                            type="text"
                            name="invite_code" required />

            <x-input-error :messages="$errors->get('invite_code')" class="mt-2" />
        </div>
        @endif

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
			
        </div>
		<br>
		<a href="{{ route('login') }}">
			{{ __('Already registered? Sign in') }}
		</a>
    </form>
@endsection