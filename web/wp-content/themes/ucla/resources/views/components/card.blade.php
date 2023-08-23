<div class="grid-card cols">
    <div class="row no-gutters inner-wrapper">

        @if ( (isset($card->header_title) && $card->header_title) || (isset($card->header_cta) && $card->header_cta) )
            <div class="header">
                @if ($card->header_title)
                    @if (isset($card->url) && $card->url)
                        <a class="header-title" target="{{ isset($card->target) ? $card->target : '' }}" href="{{ $card->url }}">
                            {!! $card->header_title !!}
                        </a>
                    @else
                        <div class="header-title">
                            {!! $card->header_title !!}
                        </div>
                    @endif
                @endif
                @if (isset($card->header_cta) && $card->header_cta)
                    @cta(["cta" => $card->header_cta]) @endcta
                @endif
            </div>
        @endif

        @if (isset($card->image_src) && $card->image_src)
            <div class="image" >
                @if ($card->url)
                    <a class="image-link" target="{{ isset($card->target) ? $card->target : '' }}" href="{{ $card->url }}">
                        <div class="sr-only">
                            @if ($card->title)
                                {!! $card->title !!}
                            @elseif ($card->header_title)
                                {!! $card->header_title !!}
                            @elseif ($card->header_cta && isset($card->header_cta->link_text))
                                {{ $card->header_cta->link_text }}
                            @elseif ($card->cta && isset($card->cta->link_text))
                                {{ $card->cta->link_text }}
                            @endif
                        </div>
                    </a>
                @endif
                @if (isset($card->image_caption) && $card->image_caption)
                    <div class="image-caption">{!! $card->image_caption !!}</div>
                @endif
                <div class="image-wrapper" style="background-image: url({{ $card->image_src }})"></div>
            </div>
        @endif

         @if (isset($card->event_box) && $card->event_box)
            <a class="event-box" target="{{ isset($card->target) ? $card->target : '' }}" href="{{ $card->url }}">
                <div class="event-date">
                    <span class="date-month">{!! $card->event_box["start_date_month"] !!}</span>
                    <span class="date-number">{!! $card->event_box["start_date"] !!}</span>
                    <span class="date-text">{!! $card->event_box["start_date_day"] !!}</span>
                </div>
                <div class="event-details">
                    {!! $card->event_box["start_time"] !!}
                    <br>
                    {!! $card->event_box["cost"] !!}
                </div>
            </a>
        @endif

        @if ( 
             (isset($card->subtitle) && $card->subtitle) || 
             (isset($card->title) && $card->title) || 
             (isset($card->text) && $card->text) || 
             (isset($card->stat) && $card->stat) || 
             (isset($card->external_links) && $card->external_links) || 
             (isset($card->cta) && $card->cta) || 
             (isset($card->caption) && $card->caption) || 
             (isset($card->tags) && $card->tags)
             )
            <div class="content">
                @if (isset($card->subtitle) && $card->subtitle)
                    <div class="subtitle">
                        {!! $card->subtitle !!}
                    </div>
                @endif

                @if (isset($card->title) && $card->title)
                    @if ($card->url)
                        <a class="title" target="{{ isset($card->target) ? $card->target : '' }}" href="{{ $card->url }}">
                            {!! $card->title !!}
                        </a>
                    @else
                        <div class="title">
                            {!! $card->title !!}
                        </div>
                    @endif
                @endif

                @if (isset($card->text) && $card->text )
                    <div class="text">
                        {!! $card->text !!}
                    </div>
                @endif

                @if (isset($card->stat) && $card->stat)
                    <div class="stat">
                        {!! $card->stat !!}
                    </div>
                @endif

                @if (isset($card->external_links) && $card->external_links)
                    @external_links(["external_links" => $card->external_links]) @endexternal_links
                @endif

                @if (isset($card->cta) && $card->cta)
                    @cta(["cta" => $card->cta]) @endcta
                @endif

                @if (isset($card->caption) && $card->caption)
                    <div class="caption">{!! $card->caption !!}</div>
                @endif

                @if (isset($card->tags) && $card->tags)
                    <div class="tags">
                        @foreach ($card->tags as $tag)
                            @if ($tag->url)
                                <a href="{{ $tag->url }}" class="tag">{!! isset($tag->link_text)  ? $tag->link_text : '' !!}</a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
