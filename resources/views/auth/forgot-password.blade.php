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
            <button type="submit"
                style="width: 100%; display: inline-flex; align-items: center; justify-content: center; padding: 0.75rem 1rem; background-color: #8B5A2B; border: none; border-radius: 0.375rem; font-weight: 600; font-size: 0.875rem; color: white; text-transform: uppercase; letter-spacing: 0.05em; cursor: pointer; transition: background-color 0.15s ease-in-out;"
                onmouseover="this.style.backgroundColor='#6F4E37'" onmouseout="this.style.backgroundColor='#8B5A2B'">
                {{ __('auth.email_reset_link') }}
            </button>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-sm text-[#8B5A2B] hover:text-[#6F4E37] hover:underline">
                &larr; Kembali ke halaman login
            </a>
        </div>
    </form>
</x-guest-layout>

