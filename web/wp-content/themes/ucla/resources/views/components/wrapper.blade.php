@if ($block->show_wrapper())
    <section id="{{ $block->id }}" class="{{ $block->classes() }}" style="{{ $block->styles() }}">
        {{ $slot }}
    </section>
@endif