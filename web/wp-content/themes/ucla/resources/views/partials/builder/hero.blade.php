<script>
    jQuery(document).ready(function($){
        $('.has-children > a').click(function(){

            $(this).parent('li').find('.child-nav').slideToggle();
            $(this).parent('li').toggleClass('expanded');
        });
    })
</script>

@wrapper(['block' => $block])
    <div class="container">

        @if( is_singular( 'tribe_events' ) )
            <div class="d-md-flex d-block">
                <div class="mr-3 ed-event-info">
                    @include('partials.tribe-events-event')
                </div>
        @endif

        <div class="row hero-row">
            <div class=" col-lg-4 ">
                <div class="header a-fade-in-up mb-2">
                    @if ( $block->header == '' )
                        <h1>{!! get_the_title() !!}</h1>
                    @else
                        {!! $block->header !!}
                    @endif
                </div>
                @if( ! is_search() && ! is_page(484) )
                    <div class="banner-nav">
                        @include('partials.nav')
                    </div>
                @endif



                {{-- figure this out --}}
                {{-- Cta url exists, contact info box not selected, header text exists --}}
                @if ($block->cta->url && !$block->show_contact_info_box && $block->header)
                    @cta(["cta" => $block->cta]) @endcta
                @endif

                @if ($block->show_contact_info_box)
                    <div class="contact-info">
                        {!! $block->contact_info !!}
                    </div>
                @endif
            </div>
            @if (!$block->has_class("image-mask") && !$block->image_mask_group)
                <div class="@if( is_singular( 'tribe_events' ) || $block->show_contact_info_box ) col-lg-8 @else col-lg-8 @endif @if ( $block->show_contact_info_box ) has-contact-box @endif ml-auto mb-3 mb-md-0">
                    @if( has_post_thumbnail() )
                        <div class="aspect-16-9"> {!! the_post_thumbnail() !!} </div>
                    @else
                        <div class="aspect-16-9"> {!! wp_get_attachment_image(get_field('sections', get_the_ID())[0]['image']['ID'], 'large') !!} </div>
                    @endif
                </div>
            @endif
        </div>

        @if ($block->has_class("image-mask") && $block->image_mask_group)
            @image_mask_group(["block" => $block, "image_mask_group" => $block->image_mask_group]) @endimage_mask_group
        @endif
        @if( is_singular( 'tribe_events' ) )
            </div>
        @endif
    </div>
@endwrapper
@if( $block->image_caption )
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-11 col-md-10 col-lg-8 d-flex justify-content-end">
                <div class="caption caption-style">{{ $block->image_caption }}</div>
            </div>
        </div>
    </div>
@endif
