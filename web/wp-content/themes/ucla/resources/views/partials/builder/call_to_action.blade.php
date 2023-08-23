@wrapper(['block' => $block])
  <div class="container">
    <div class="row call-to-action-row">
        <div class="call-to-action-wrapper cols">
            <div class="inner-wrapper">
                @if ($block->has_class("blue-bg") && $block->header)
                    <div class="header a-fade-in-up">
                        {!! $block->header !!}
                    </div>
                @elseif ($block->content)
                    <div class="content">
                        {!! $block->content !!}
                    </div>
                @endif

                @if (!empty($block->links_cta))
                    <div class="links">
                        @foreach($block->links_cta as $cta)
                            @if ($cta->url)
                                @cta(["cta" => $cta]) @endcta
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
  </div>
@endwrapper
