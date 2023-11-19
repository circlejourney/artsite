<button {{ $attributes->merge(['type' => 'button', 'class' => 'button-pill']) }}>
    {{ $slot }}
</button>
