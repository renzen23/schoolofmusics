@wrapper(['block' => $block])
  <div class="container">
    <div class="row profile-row">

        <div class="header-wrapper cols">

          @if (!empty($block->tags))
            <div class="tags">
              @foreach ($block->tags as $tag)
                <a class="tag">{!! $tag->name !!}</a>
              @endforeach
            </div>
          @endif

          <div class="header">
            {!! $block->header !!}
          </div>

          <div class="subtitle">
            {!! $block->title !!}
          </div>

          <div class="description">
            {!! $block->description !!}
          </div>

        </div>

        <div class="secondary-wrapper cols"><div class="portrait" style="background-image: url({{ $block->image_src }})"></div>

          @if ($block->quote)
            <div class="quote">
              {!! $block->quote !!}
            </div>
          @endif

          @if ($block->external_links)
              @if ($block->email)
                  @php
                      array_unshift($block->external_links, ['url' => 'mailto:' . $block->email, 'external_link_icon' => 'email-icon']);
                  @endphp
              @endif
              @external_links(["external_links" => $block->external_links]) @endexternal_links
          @endif

        </div>

        <div class="text-wrapper cols">
          <div class="text auto-more">
              {!! $block->bio !!}
          </div>
        </div>

    </div>
  </div>
@endwrapper
