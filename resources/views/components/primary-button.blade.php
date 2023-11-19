<button {{ $attributes->merge(['type' => 'submit', 'class' => 'button-pill']) }}>
    {{ $slot }}
</button>
