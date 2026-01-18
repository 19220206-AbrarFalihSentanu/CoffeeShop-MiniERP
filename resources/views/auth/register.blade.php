<x-guest-layout>
    <h4 class="text-xl font-bold text-center text-[#8B5A2B] mb-2">{{ __('auth.register') }}</h4>
    <p class="text-center text-gray-500 text-sm mb-6">Buat akun baru untuk berbelanja</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('auth.name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('auth.email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('auth.password')" />
            <div class="password-wrapper mt-1">
                <input id="password"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"
                    type="password" name="password" required autocomplete="new-password"
                    style="padding-right: 2.5rem;" />
                <button type="button" onclick="togglePassword('password')" class="password-toggle"
                    aria-label="Toggle password visibility">
                    <svg id="password-eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg id="password-eye-off" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter</p>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('auth.confirm_password')" />
            <div class="password-wrapper mt-1">
                <input id="password_confirmation"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#8B5A2B] focus:ring-[#8B5A2B]"
                    type="password" name="password_confirmation" required autocomplete="new-password"
                    style="padding-right: 2.5rem;" />
                <button type="button" onclick="togglePassword('password_confirmation')" class="password-toggle"
                    aria-label="Toggle password visibility">
                    <svg id="password_confirmation-eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg id="password_confirmation-eye-off" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Register Button -->
        <div style="margin-top: 1.5rem;">
            <button type="submit"
                style="width: 100%; display: inline-flex; align-items: center; justify-content: center; padding: 0.75rem 1rem; background-color: #8B5A2B; border: none; border-radius: 0.375rem; font-weight: 600; font-size: 0.875rem; color: white; text-transform: uppercase; letter-spacing: 0.05em; cursor: pointer; transition: background-color 0.15s ease-in-out;"
                onmouseover="this.style.backgroundColor='#6F4E37'" onmouseout="this.style.backgroundColor='#8B5A2B'">
                {{ __('auth.register') }}
            </button>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-200 text-center">
            <p class="text-gray-600 text-sm">{{ __('auth.already_registered') }}</p>
            <a href="{{ route('login') }}"
                class="mt-2 inline-block text-[#8B5A2B] hover:text-[#6F4E37] font-semibold hover:underline">
                {{ __('auth.login') }}
            </a>
        </div>
    </form>

    <script>
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(fieldId + '-eye');
            const eyeOffIcon = document.getElementById(fieldId + '-eye-off');

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }
    </script>
</x-guest-layout>

