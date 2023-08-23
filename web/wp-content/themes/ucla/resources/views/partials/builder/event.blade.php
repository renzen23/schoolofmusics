@wrapper(['block' => $block])
@php
  $event_id = get_the_ID();
  $post_meta = get_post_meta($event_id,'_ecp_custom_6')[0];
  $livestream = strpos($post_meta, 'Live Stream') !== false;
@endphp
    <div class="container">
		<div class="row event-row">
            <div class="content">
                <div class="categories">
                    {!! $block->categories !!}
                </div>
                <!--<div class="title">
                    {!! $block->title !!}
                </div>-->
                <div class="subtitle">
                    {!! $block->subtitle !!}
                    @if($livestream)
                      <a href="/school-of-music-live-streams/#{{ sanitize_title($block->subtitle) }}" class="btn btn-secondary">Watch Livestream</a>
                    @endif

                    @if (get_field('program') && get_field('show_program'))
                    <a href="{{ get_permalink() . "program" }}" class="btn btn-secondary">View Program</a>
                    @endif
                </div>
                <div class="text">
                    {!! do_shortcode($block->content) !!}
                </div>

                @external_links(["external_links" => $block->external_links]) @endexternal_links
            </div>

        </div>
    </div>
@endwrapper
