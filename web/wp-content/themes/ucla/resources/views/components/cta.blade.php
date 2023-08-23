<a {!! isset($cta->url) ? "href='$cta->url'" : false !!} {{ isset($cta->target) && $cta->target ? "target=".$cta->target : false }} class="cta {{ $cta->classes() }}">
    @if (isset($cta->emphasized_link_text) && $cta->emphasized_link_text )
        <div class="em-link-text">{!! $cta->emphasized_link_text !!}</div>
    @endif
    @if ($cta->has_class("em-calendar-icon"))
        <img src="@asset('images/calendar.svg')" class="svg em-icon" alt="calendar" />
        <div class="sr-only">Calendar</div>
    @endif
    @if (!$cta->has_class("pill-cta"))
        <div class="icon">
            @php
                $arrow_icon = ($cta->has_class("circle-cta") && !$cta->has_class("small")) ? "arrow" : "arrow-bold";
            @endphp
            <img src="@asset('images/'.$arrow_icon.'.svg')" class="svg" alt="{{ isset($cta->link_text) ? $cta->link_text : '' }}" />
            @if (isset($cta->link_text))
                <div class="sr-only">{!! $cta->link_text !!}</div>
            @endif
        </div>
    @endif
    @if (isset($cta->link_text))
        <span>{!! $cta->link_text !!}</span>
    @endif
</a>
