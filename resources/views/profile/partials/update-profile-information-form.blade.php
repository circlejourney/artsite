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
        <h2>
            {{ __('Profile Information') }}
        </h2>

        <p>
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="form-group">
            <x-input-label for="name" value="Username" />
            <x-text-input id="name" name="name" type="text" class="form-control" :value="old('name', $user->name)" required autofocus autocomplete="Username" oninput="updateFlairPreview()" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="form-group">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="form-control" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="form-control">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                </div>
            @endif
        </div>

        <div class="form-group">
            <x-input-label for="display_name" value="Display name" />
            <x-text-input id="display_name" name="display_name" type="text" class="form-control" :value="old('display_name', $user->display_name)" required autofocus autocomplete="Display name" />
            <x-input-error class="mt-2" :messages="$errors->get('display_name')" />
        </div>

		@if($user->hasPermissions("change_own_flair"))
        <div class="form-inline">
            <div class="form-group">
                <label for="custom_flair">Custom icon flair: <b>fa-</b></label>
                <input id="custom_flair" name="custom_flair" type="text" class="form-control" value="{{ old('custom_flair', $user->custom_flair ?? $user->getTopRole()->default_flair) }}" required autofocus oninput="updateFlairPreview()">
            </div>
            <div class="ml-2">
                <a href="https://fontawesome.com/search?o=r&m=free">Click here</a> for a list of available Font Awesome icon names.
                <x-input-error class="mt-2" :messages="$errors->get('custom_flair')" />
            </div>
        </div>
		@endif
		
		<div>
			Preview: <a href="#"><i class="flair-preview fa fa-{{ old('custom_flair', $user->custom_flair ?? $user->getTopRole()->default_flair) }}"></i> <span class="username-preview"></span></a>
		</div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>
</section>
