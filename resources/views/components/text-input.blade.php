@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-2 border-primary rounded-none focus:border-accent focus:ring-accent']) }}>
