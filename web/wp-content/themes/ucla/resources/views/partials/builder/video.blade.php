@wrapper(['block' => $block])

    @php
        if ($block->has_class("livestream")) {
            $livestream_data = explode('/', $block->url);
            $livestream_src = "https://livestream.com/accounts/".$livestream_data[4]."/events/".$livestream_data[6]."/player?width=640&height=360&enableInfoAndActivity=false&defaultDrawer=false&autoPlay=true&mute=false";
        } else {
            $oembed = _wp_oembed_get_object();
            $provider = $oembed->get_provider($block->url);
            $oembed_data = $oembed->fetch( $provider, $block->url);

            if (is_object($oembed_data) && $oembed_data->provider_name == "YouTube") {
              $thumbnail = $oembed_data->thumbnail_url;
              $thumbnail_fallback = str_replace("/hq","/sd",$oembed_data->thumbnail_url);
            } else if (is_object($oembed_data) && $oembed_data->provider_name == "Vimeo") {
              $thumbnail = str_replace("295x166","640x476", $oembed_data->thumbnail_url);
              $thumbnail_fallback = $oembed_data->thumbnail_url;
            } else {
              $thumbnail = false;
            }
        }
    @endphp

    <div class="container">

        {{-- Show standard blue header --}}
        @if (!$block->has_class("black-bg"))
            @section_header(["section_header" => $block->section_header]) @endsection_header
        @endif

        <div class="row no-gutters video-row">
            <div class="content cols">

                {{-- Show hero style header --}}
                @if ($block->has_class("black-bg"))
                    <div class="fade-edges">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    @if ($block->video_header)
                        <div class="header">
                            {!! $block->video_header !!}
                        </div>
                    @endif
                @endif

                @if ($block->has_class("livestream"))
                    <div class="embed-container">
                        <iframe src="{{ $livestream_src }}" width="640" height="360" frameborder="0" scrolling="no" allowfullscreen> </iframe>
                    </div>
                @else
                    <div class="video-trigger">
                        <img src="@asset('images/play.svg')" class="play-button" alt="Play">
                    </div>

                    @if ($thumbnail)
                         <div class="embed-container img-preview" data-iframe="{{ $block->video }}">
                            <img src="{!! $thumbnail !!}" onerror="this.onerror = null; this.src='{!! $thumbnail_fallback !!}'" alt="{{ $block->video_header }}"/>
                        </div>
                    @else
                        <div class="embed-container" data-iframe="{{ $block->video }}">
                            {!! $block->video !!}
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>
@endwrapper
