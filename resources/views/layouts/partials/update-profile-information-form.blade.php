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

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="mt-4">
            <x-input-label for="profile_picture" value="Profile Picture" />
            @if ($user->profile_picture)
                <img src="{{ asset('storage/' . $user->profile_picture) }}"
                    class="w-20 h-20 rounded-full object-cover mb-2">
            @endif
            <input type="file" name="profile_picture" id="profile_picture"
                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
            <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
        </div>

        <div class="mt-4">
            <x-input-label for="phone" value="Phone Number" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div class="mt-4">
            <x-input-label for="bio" value="Bio" />
            <textarea id="bio" name="bio"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

    </form>
</section>
