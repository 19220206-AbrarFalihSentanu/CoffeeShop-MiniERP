<x-guest-layout>
    <h4 class="text-xl font-bold text-center text-[#8B5A2B] mb-2">Verifikasi Email</h4>

    <div class="mb-4 text-sm text-gray-600 text-center">
        {{ __('auth.verify_email_text') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div
            class="mb-4 font-medium text-sm text-green-600 bg-green-50 border border-green-200 rounded-md p-3 text-center">
            {{ __('auth.verification_link_sent') }}
        </div>
    @endif

    <div class="mt-6 flex flex-col gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full justify-center py-3">
                {{ __('auth.resend_verification') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="text-sm text-[#8B5A2B] hover:text-[#6F4E37] hover:underline">
                {{ __('auth.logout') }}
            </button>
        </form>
    </div>
</x-guest-layout>
