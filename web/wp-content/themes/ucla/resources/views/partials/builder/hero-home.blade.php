@wrapper(['block' => $block])
    <div class="container">
        <div class="row no-gutters hero-row">
            <div class="content cols">
                @if ($block->header)
                    <div class="header a-fade-in-up">
                        {!! $block->header !!}
                    </div>
                @else
                    <h1 class="sr-only">
                        {!! get_the_title() !!}
                    </h1>
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
        </div>

        @if ($block->has_class("image-mask") && $block->image_mask_group)
            @image_mask_group(["block" => $block, "image_mask_group" => $block->image_mask_group]) @endimage_mask_group
        @endif

    </div>
@endwrapper