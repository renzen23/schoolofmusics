@wrapper(['block' => $block])
<div class="container">
    <div class="row navigation-carousel-row">
        <div class="content cols">
            @if (!empty($block->categories))
                @foreach($block->categories as $ul)
                    <div class="header a-fade-in-up">
                        {!! $ul["header"] !!}
                    </div>
                    @if (!empty($ul))
                        <ul>
                        @foreach($ul["items"] as $li)
                            <li>
                                @if ($li["link"])
                                    <a class="link" target="{{ $li["link"]["target"] }}" href="{{ $li["link"]["url"] }}">
                                        @if ($li["title"])
                                            <div class="sr-only">{!! $li["title"] !!}</div>
                                        @endif
                                    </a>
                                @endif
                                @if ($li["title"])
                                    <div class="title">
                                        {!! $li["title"] !!}
                                    </div>
                                @endif
                                @if ($li["text"])
                                    <div class="text">
                                        {!! $li["text"] !!}
                                    </div>
                                @endif
                                @if (!empty($li["link"]))
                                    @cta(["cta" => $block->cta]) @endcta
                                @endif
                            </li>
                        @endforeach
                        </ul>
                    @endif
                @endforeach
            @endif
        </div>
    </div>

    @image_mask_group(["image_mask_group" => $block->image_mask_group]) @endimage_mask_group

</div>
@endwrapper