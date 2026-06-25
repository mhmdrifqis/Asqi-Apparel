<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn border-danger bg-danger text-white hover:bg-white hover:text-danger focus:ring-danger']) }}>
    {{ $slot }}
</button>
