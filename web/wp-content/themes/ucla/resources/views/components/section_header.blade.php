@if ( (isset($section_header->header) && $section_header->header ) ||  
        (isset($section_header->is_slider) && $section_header->is_slider) || 
        (isset($section_header->cta) && $section_header->cta) )
    <div class="row no-gutters section-header-row">

        @if (isset($section_header->header) && $section_header->header)
            <h3 class="header a-fade-in-up">{!! $section_header->header !!}</h3>
        @endif

        @if (isset($section_header->is_slider) && $section_header->is_slider)
            <div class="slick-dots-wrapper">
                {{-- slick dots appended here  --}}
            </div>
            @if($section_header->slider_cta_text)
              <a class="slick-cta-text" href="{!! $section_header->slider_cta_url !!}">{!! $section_header->slider_cta_text !!}</a>
            @endif
            @slick_arrows() @endslick_arrows
        @endif

        @if (isset($section_header->cta) && $section_header->cta)
            @cta(["cta" => $section_header->cta]) @endcta
        @endif

        @if (isset($section_header->subheader) && $section_header->subheader)
            <div class="subheader">{!! $section_header->subheader !!}</div>
        @endif

    </div>
@endif
