@wrapper(['block' => $block])
  <div class="container">
    <div class="row quotes-row">

        <div class="content cols">
            @if ($block->header)
                <div class="header">
                    {!! $block->header !!}
                </div>
            @endif

            @if (!empty($block->quotes))
                <div class="quote-wrapper">
                    @foreach($block->quotes as $quote)
                        <div class="quote">
                            @if ($quote["author"])
                                <div class="title">
                                    {!! $quote["author"] !!}
                                </div>
                            @endif
                            @if ($quote["quote"])
                                <div class="text">
                                    {!! $quote["quote"] !!}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if (!empty($block->quotes) && array_key_exists(1, $block->quotes))
                    <div class="quote-nav">
                        <div class="slick-dots-wrapper">
                            {{-- slick dots appended here  --}}
                        </div>
                        @slick_arrows(["block" => $block]) @endslick_arrows
                    </div>
                @endif
            @endif
        </div>
    </div>

    @image_mask_group(["image_mask_group" => $block->image_mask_group]) @endimage_mask_group

</div>
@endwrapper