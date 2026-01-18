<x-guest-layout>
    <h4 class="text-xl font-bold text-center text-[#8B5A2B] mb-2">Konfirmasi Password</h4>

    <div class="mb-4 text-sm text-gray-600 text-center">
        {{ __('auth.confirm_password_text') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('auth.password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3">
                {{ __('general.confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>


