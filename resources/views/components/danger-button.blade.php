<button {{ $attributes->merge(['type' => 'submit', 'class' => 'button bg-danger']) }}>
    {{ $slot }}
</button>
