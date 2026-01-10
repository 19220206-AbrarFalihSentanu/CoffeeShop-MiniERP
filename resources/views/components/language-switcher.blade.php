{{-- File: resources/views/components/language-switcher.blade.php --}}
@php
    $currentLocale = app()->getLocale();
    $languages = [
        'id' => ['name' => 'Indonesia', 'flag' => '🇮🇩', 'short' => 'ID'],
        'en' => ['name' => 'English', 'flag' => '🇺🇸', 'short' => 'EN'],
    ];
@endphp

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
        aria-expanded="false">
        <span class="me-1">{{ $languages[$currentLocale]['flag'] }}</span>
        <span class="d-none d-md-inline">{{ $languages[$currentLocale]['short'] }}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <h6 class="dropdown-header">{{ __('general.select_language') }}</h6>
        </li>
        @foreach ($languages as $locale => $lang)
            <li>
                <a class="dropdown-item {{ $currentLocale === $locale ? 'active' : '' }}"
                    href="{{ route('language.switch', $locale) }}">
                    <span class="me-2">{{ $lang['flag'] }}</span>
                    {{ $lang['name'] }}
                    @if ($currentLocale === $locale)
                        <i class="bx bx-check ms-auto"></i>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</li>
