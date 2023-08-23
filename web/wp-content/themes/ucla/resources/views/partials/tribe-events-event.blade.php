@php
    $block = new App\Builder\SingleBlock('tribe_events_event');
    call_user_func(array($block, 'tribe_events_event'));
@endphp
<section class="b-event">
    <div class="event-box">
        <div class="event-date">
            <span class="date-month">{!! $block->event_box["start_date_month"] !!}</span>
            <span class="date-number">{!! $block->event_box["start_date"] !!}</span>
            <span class="date-text">{!! $block->event_box["start_date_day"] !!}</span>
        </div>
        <div class="event-details">
            {!! $block->event_box["start_time"] !!}
            <br>
            {!! $block->event_box["cost"] !!}
        </div>
        <div class="calendar-links">
            <a href="{!! $block->event_box["ical"] !!}"><img src="@asset('images/calendaradd.svg')" class="svg"><span>Add to Calendar</span></a>
            <a href="{!! $block->event_box["gcal"] !!}"><img src="@asset('images/calendaraddgcal.svg')" class="svg"><span>Add to Google Calendar</span></a>
        </div>

        @if ($block->social_share_links)
            <ul class="social-shares">
            @foreach($block->social_share_links as $link)
                <li class="social-share {{ $link }}" data-text="{{ $link == "twitter" ? $block->title : null }}">
                    <i class="fab fa-{{ $link }}"></i>
                    <span>{{ $link != "twitter" ? "Share" : "Tweet" }}</span>
                </li>
            @endforeach
            </ul>
        @endif

    </div>
</section>