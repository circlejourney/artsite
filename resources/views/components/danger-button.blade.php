<button {{ $attributes->merge(['type' => 'submit', 'class' => 'button-pill bg-danger']) }}>
    {{ $slot }}
</button>
