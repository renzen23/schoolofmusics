@wrapper(['block' => $block])
    <div class="container">
      <div class="row no-gutters section-header-row">
        <div class="header">{!! $block->title !!}</div>
        @section_header(["section_header" => $block->title]) @endsection_header
      </div>
      <div id="research-listing">
          <research-directory></research-directory>
      </div>
    </div>
@endwrapper
