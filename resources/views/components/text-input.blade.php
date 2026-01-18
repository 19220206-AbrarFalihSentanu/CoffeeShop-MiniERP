@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'border-gray-300 focus:border-[#8B5A2B] focus:ring-[#8B5A2B] rounded-md shadow-sm',
]) !!}>


