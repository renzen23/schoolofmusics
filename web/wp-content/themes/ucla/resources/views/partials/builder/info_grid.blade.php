@wrapper(['block' => $block])
    <div class="container">
        @section_header(["section_header" => $block->section_header]) @endsection_header
        @if (get_post_type() == "tribe_events")
            <div class="row">
                <div class="col-12 col-md-6">
                    @if( $block->info_grid['ticket_override'] == 'true' )
                        {!! $block->info_grid['tickets_info'] !!}
                    @else
                        {!! $block->info_grid['tickets'] !!}
                    @endif
                    {!! $block->info_grid['left_column_info'] !!}
                </div>
                <div class="col-12 col-md-6">
                    {!! $block->info_grid['right_column_info'] !!}
                </div>
            </div>
        @else
            @card_grid(["card_grid" => $block->card_grid]) @endcard_grid
        @endif
    </div>
@endwrapper
