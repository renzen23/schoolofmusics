@wrapper(['block' => $block])
    <div class="container">
        @section_header(["section_header" => $block->section_header]) @endsection_header
        @card_grid(["card_grid" => $block->card_grid]) @endcard_grid
    </div>
@endwrapper
