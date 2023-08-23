@php
    // TRIBE EVENTS TEMPLATE LOCATION
    // [your-theme]/tribe-events/single-event.php

    $Singleblock = 'App\\Builder\\SingleBlock';
    $builder_modules = [
        "hero",
        "event",
        "call_to_action",
        "video",
        "info_grid",
        "events_grid",
    ];
@endphp

@if (get_field('program') && get_field('show_program'))
<div class="event__program-link bg-yellow">
    <div class="container">
        <div class="row justify-content-end">
            <div class="col-12 text-right">
                <a href="{{ get_permalink() . "program" }}" class="text-uppercase">View Program <img src="@asset('images/arrow-bold.svg')" class="svg" alt="Show Program" /></a>
            </div>
        </div>
    </div>
</div>
@endif

@php 
    $block = new App\Builder\SingleBlock('hero');
    call_user_func([$block, 'tribe_events_hero']);
@endphp
@includeIf('partials.builder.hero', ['block' => $block])

<div class="container d-lg-flex">
    @php 
        $block = new App\Builder\SingleBlock('event');
        call_user_func([$block, 'tribe_events_event']);
    @endphp
    <div class="no-border" style="flex-grow: 1; flex-basis: 0; padding-right:1rem;">
        @includeIf('partials.builder.event', ['block' => $block])
    </div>

    <div class="on-event-page" style="flex-grow: 1; flex-basis: 0;">
        @php 
            $block = new App\Builder\SingleBlock('video');
            call_user_func([$block, 'tribe_events_video']);
        @endphp
        <div style="margin-top:3rem;" class="no-border">
            @includeIf('partials.builder.video', ['block' => $block])
        </div>

        @php 
            $block = new App\Builder\SingleBlock('call_to_action');
            call_user_func([$block, 'tribe_events_call_to_action']);
        @endphp
        <div class="no-border">
            @includeIf('partials.builder.call_to_action', ['block' => $block])
        </div>
    </div>
</div>

@php 
    $block = new App\Builder\SingleBlock('info_grid');
    call_user_func([$block, 'tribe_events_info_grid']);
@endphp
@includeIf('partials.builder.info_grid', ['block' => $block])

@php 
    $block = new App\Builder\SingleBlock('events_grid');
    call_user_func([$block, 'tribe_events_events_grid']);
@endphp
@includeIf('partials.builder.events_grid', ['block' => $block])
