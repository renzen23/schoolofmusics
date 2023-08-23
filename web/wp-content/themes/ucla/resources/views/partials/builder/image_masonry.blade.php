@wrapper(['block' => $block])
  <div class="container">

    @section_header(["section_header" => $block->section_header]) @endsection_header

    <div class="masonry-container">
      <div class="masonry-sizer"></div>
      @php
        $image_chunks = array_chunk($block->images, $block->number);
        $i = 0;
      @endphp
      @foreach( $image_chunks as $key=>$chunk )
        @if( $key == 0 )
          @foreach( $chunk as $image )
          <div class="masonry-item brick-{!! $image['brick_size'] !!} chunk-{!! $key !!}" data-chunk="{!! $key !!}" data-image-id="{!! $i++ !!}">
            @if($image['type'] == 'image')
              <div class="masonry-image lazyload" style="background-image: url({{ wp_get_attachment_image_src($image['image']['ID'],'medium_large')[0] }})"></div>
            @elseif($image['type'] == 'video')
              @php
                $url = $block->url[$i - 1];
                $oembed = _wp_oembed_get_object();
                $provider = $oembed->get_provider($url);
                $oembed_data = $oembed->fetch($provider, $url);
                if (is_object($oembed_data) && $oembed_data->provider_name == "YouTube") {
                  $thumbnail = $oembed_data->thumbnail_url;
                  $thumbnail_fallback = str_replace("/hq","/sd",$oembed_data->thumbnail_url);
                } else if (is_object($oembed_data) && $oembed_data->provider_name == "Vimeo") {
                  $thumbnail = str_replace("295x166","640x476", $oembed_data->thumbnail_url);
                  $thumbnail_fallback = $oembed_data->thumbnail_url;
                } else {
                  $thumbnail = false;
                }
              @endphp
              <div class="masonry-image lazyload" style="background-image: url({!! $thumbnail !!}), url({!! $thumbnail_fallback !!})">
                <img src="@asset('images/play.svg')" class="play-button" alt="Play">
              </div>
            @endif
          </div>
          @endforeach
        @else
          @foreach( $chunk as $image )
          <div class="masonry-item brick-{!! $image['brick_size'] !!} chunk-{!! $key !!} hidden" data-chunk="{!! $key !!}" data-image-id="{!! $i++ !!}">
            @if($image['type'] == 'image')
              <div class="masonry-image lazyload" style="background-image: url({{ wp_get_attachment_image_src($image['image']['ID'],'medium_large')[0]  }})"></div>
            @elseif($image['type'] == 'video')
              @php
                $url = $block->url[$i - 1];
                $oembed = _wp_oembed_get_object();
                $provider = $oembed->get_provider($url);
                $oembed_data = $oembed->fetch($provider, $url);
                if (is_object($oembed_data) && $oembed_data->provider_name == "YouTube") {
                  $thumbnail = $oembed_data->thumbnail_url;
                  $thumbnail_fallback = str_replace("/hq","/sd",$oembed_data->thumbnail_url);
                } else if (is_object($oembed_data) && $oembed_data->provider_name == "Vimeo") {
                  $thumbnail = str_replace("295x166","640x476", $oembed_data->thumbnail_url);
                  $thumbnail_fallback = $oembed_data->thumbnail_url;
                } else {
                  $thumbnail = false;
                }
              @endphp
              <div class="masonry-image lazyload" style="background-image: url({!! $thumbnail !!}), url({!! $thumbnail_fallback !!})">
                <img src="@asset('images/play.svg')" class="play-button" alt="Play">
              </div>
            @endif
          </div>
          @endforeach
        @endif
      @endforeach
    </div>
    @if ( $i > 2 )
      <button class="masonry-load-more">Load More</button>
    @endif
  </div>
@endwrapper

<div class="modal fade masonry-modal" id="masonry-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="masonry-prev"><img src="@asset('images/arrow-left.svg')" class="svg"></div>
        <div class="masonry-next"><img src="@asset('images/arrow-right.svg')" class="svg"></div>
        <div class="masonry-modal-slider">
          @foreach( $image_chunks as $key=>$chunk )
            @foreach( $chunk as $image )
              @if($image['type'] == 'image')
                <div id="{!! $image['image']['id'] !!}">
                  <div class="img" style="background-image: url({{ wp_get_attachment_image_src($image['image']['ID'],'large')[0] }})"></div>
                  @if ( $image['caption'] )
                      <div class="slick-caption">
                        {{ $image['caption'] }}
                      </div>
                    @endif
                </div>
              @elseif($image['type'] == 'video')
                <div id="{!! $image['image']['id'] !!}">
                  <div class="embed-container">
                    {!! $image['video'] !!}
                  </div>
                    @if ( $image['caption'] )
                      <div class="slick-caption">
                        {{ $image['caption'] }}
                      </div>
                    @endif
                </div>
              @endif
            @endforeach
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
