@wrapper(['block' => $block])
    <div class="container">
      <div class="row no-gutters section-header-row">
        @if ($block->section_header->header)
            <h3 class="header a-fade-in-up">{!! $block->section_header->header !!}</h3>
        @endif
      </div>

      <div class="row no-gutters">
      @if ($block->press_related->components)
        <div class="related-press">
        @foreach ($block->press_related->components as $related_press)
          <h4 class="mb-3"><a href="{{ get_permalink($related_press->id) }}" target="_blank">{{ $related_press->title }}</a></h4>
        @endforeach
        </div>
      @endif
      </div><!-- .row -->
    </div><!-- .container -->
@endwrapper
