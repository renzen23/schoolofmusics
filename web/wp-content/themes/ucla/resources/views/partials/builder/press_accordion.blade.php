@wrapper(['block' => $block])
    <div class="container">
        @section_header(["section_header" => $block->section_header]) @endsection_header
        @accordion_group(["accordion_group" => $block->accordion_group]) @endaccordion_group
    </div>
@endwrapper