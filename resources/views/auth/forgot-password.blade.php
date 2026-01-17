<x-guest-layout>
    <h4 class="text-xl font-bold text-center text-[#8B5A2B] mb-2">{{ __('auth.forgot_password') }}</h4>

    <div class="mb-4 text-sm text-gray-600 text-center">
        {{ __('auth.forgot_password_text') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('auth.email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3">
                {{ __('auth.email_reset_link') }}
            </x-primary-button>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-sm text-[#8B5A2B] hover:text-[#6F4E37] hover:underline">
                &larr; Kembali ke halaman login
            </a>
        </div>
    </form>
</x-guest-layout>
