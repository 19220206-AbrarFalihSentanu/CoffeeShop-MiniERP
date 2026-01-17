<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#8B5A2B] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#6F4E37] focus:bg-[#6F4E37] active:bg-[#5D4037] focus:outline-none focus:ring-2 focus:ring-[#8B5A2B] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
