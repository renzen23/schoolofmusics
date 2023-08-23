@wrapper(['block' => $block])
    <div class="container">
        <div class="row info-content-row">
            @if ($block->primary_header || !empty($block->links_cta) || $block->details || !empty($block->details_links_cta || $block->has_class("image-block")))
                <div class="header-content cols">

                    @if ($block->primary_header)
                        <h2 class="header a-fade-in-up">
                            {!! $block->primary_header !!}
                        </h2>
                    @endif

                    @if (!empty($block->header_links_cta))
                        <div class="header-links">
                            @foreach($block->header_links_cta as $cta)
                                @if ($cta->url)
                                    @cta(["cta" => $cta]) @endcta
                                @endif
                                @if (!$loop->last)
                                    <div class="w-100"></div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    @if ($block->details || !empty($block->details_links_cta))
                        <div class="details-group">
                            @if ($block->details)
                                <div class="details">
                                    {!! $block->details !!}
                                </div>
                            @endif

                            @if (!empty($block->details_links_cta))
                                <div class="details-links">
                                    @foreach($block->details_links_cta as $cta)
                                        @if ($cta->url)
                                            @cta(["cta" => $cta]) @endcta
                                        @endif
                                        @if (!$loop->last)
                                            <div class="w-100"></div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                    @if ($block->has_class("image-block"))
                        @if ( $block->cta->url != '' )
                            <a href="{{ $block->cta->url }}">
                        @endif
                            <div class="image-block a-fade-in">
                                <img src="{{ $block->image_src }}" alt="{{ $block->image_caption ? $block->image_caption : $block->title }}">
                                @if ($block->image_caption)
                                    <div class="image-caption">{!! $block->image_caption !!}</div>
                                @endif
                            </div>
                        @if ( $block->cta->url != '' )
                            </a>
                        @endif
                    @endif

                </div>
            @endif

            @if ($block->title || $block->body_text || $block->cta->url)
                <div class="content cols">
                    @if ($block->title)
                        <div class="title">
                            {!! $block->title !!}
                        </div>
                    @endif

                    @if ($block->body_text)
                        <div class="text">
                            {!! $block->body_text !!}
                        </div>
                    @endif

                    @if ($block->external_links)
                        @external_links(["external_links" => $block->external_links]) @endexternal_links
                    @endif
                    
                    @if ($block->cta->url)
                        @cta(["cta" => $block->cta]) @endcta
                    @endif

                </div>
            @endif
        </div>

        @if ($block->has_class("image-mask"))
            @image_mask_group(["block" => $block, "image_mask_group" => $block->image_mask_group]) @endimage_mask_group
        @endif

    </div>
@endwrapper
