 @php
    if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
        wpcf7_enqueue_scripts();
    }
 
    if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
        wpcf7_enqueue_styles();
    }
@endphp

@wrapper(['block' => $block])
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-xl-8">
              {!! do_shortcode("[contact-form-7 id='" . $block->form_id . "']") !!}
            </div>
        </div>
    </div>
@endwrapper
