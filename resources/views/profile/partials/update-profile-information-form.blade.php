@push('head')
	<script>
		function updateFlairPreview() {
			if($("#custom_flair").length) $(".flair-preview").removeClass().addClass("flair-preview fa fa-"+$("#custom_flair").val());
			$(".username-preview").text($("#name").val());
		}
		$(window).on("load", updateFlairPreview);
	</script>
@endpush

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Username" />
            <x-text-input id="name" name="name" type="text" class="mt-1" :value="old('name', $user->name)" required autofocus autocomplete="Username" oninput="updateFlairPreview()" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="display_name" value="Display name" />
            <x-text-input id="display_name" name="display_name" type="text" class="mt-1" :value="old('display_name', $user->display_name)" required autofocus autocomplete="Display name" />
            <x-input-error class="mt-2" :messages="$errors->get('display_name')" />
        </div>

		@if($user->hasPermissions("change_own_flair"))
        <div>
            <label for="custom_flair">Custom icon flair: <b>fa-</b></label>
			<input id="custom_flair" name="custom_flair" type="text" class="mt-1" value="{{ old('custom_flair', $user->custom_flair ?? $user->getTopRole()->default_flair) }}" required autofocus oninput="updateFlairPreview()">
			<a href="https://fontawesome.com/search?o=r&m=free">Click here</a> for a list of available Font Awesome icon names.
            <x-input-error class="mt-2" :messages="$errors->get('custom_flair')" />
        </div>
		@endif
		
		<div>
			Preview: <a href="#"><i class="flair-preview fa fa-{{ old('custom_flair', $user->custom_flair ?? $user->getTopRole()->default_flair) }}"></i> <span class="username-preview"></span></a>
		</div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
