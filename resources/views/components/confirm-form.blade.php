@props([
    'action',
    'method' => 'POST',
    'title' => 'Konfirmasi',
    'text' => 'Apakah Anda yakin?',
    'confirmText' => 'Ya, Lanjutkan!',
    'cancelText' => 'Batal',
    'icon' => 'question',
    'buttonClass' => '',
    'buttonText' => '',
    'buttonIcon' => '',
    'isDanger' => false,
])

@php
    $formId = 'confirm-form-' . uniqid();
    $buttonType = $isDanger ? 'danger' : 'primary';
@endphp

<form id="{{ $formId }}" action="{{ $action }}" method="POST" class="d-inline">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif
    {{ $slot }}
    <button type="button" class="{{ $buttonClass ?: 'btn btn-' . $buttonType }}"
        onclick="confirmFormSubmit('{{ $formId }}', '{{ $title }}', '{{ $text }}', '{{ $confirmText }}', '{{ $cancelText }}', '{{ $icon }}', {{ $isDanger ? 'true' : 'false' }})">
        @if ($buttonIcon)
            <i class="{{ $buttonIcon }} me-1"></i>
        @endif
        {{ $buttonText }}
    </button>
</form>

@once
    @push('scripts')
        <script>
            function confirmFormSubmit(formId, title, text, confirmText, cancelText, icon, isDanger) {
                swalCoffee.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: cancelText,
                    confirmButtonColor: isDanger ? '#dc3545' : '#8B5A2B',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit();
                    }
                });
            }
        </script>
    @endpush
@endonce


