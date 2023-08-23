@if (!empty($external_links[0]["url"]))
    <div class="external-links">
        @foreach ($external_links as $link)
            @if (!empty($link["url"]))
                <a href="{{ $link["url"] }}" target="_blank" class="icon circle lightgrey">
                    @if( $link['external_link_icon'] == 'link-icon' )
                        <img src="@asset('images/link.svg')" class="svg" alt="{{ $link["url"] }}">
                        <div class="sr-only">{!! $link["url"] !!}</div>
                    @elseif( $link['external_link_icon'] == 'email-icon' )
                        <img src="@asset('images/mail.svg')" class="svg" alt="{{ $link["url"] }}">
                        <div class="sr-only">{!! $link["url"] !!}</div>
                    @else
                        <i class="fab fa-{!! $link['external_link_icon'] !!}"></i>
                        <div class="sr-only">{!! $link["external_link_icon"] !!}</div>
                    @endif
                </a>
            @endif
        @endforeach
    </div>
@endif
