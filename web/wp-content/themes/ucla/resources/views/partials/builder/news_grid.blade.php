@wrapper(['block' => $block])
    <div class="container">
        @section_header(["section_header" => $block->section_header]) @endsection_header

        @if ($block->news_hero)
          <div class="row grid-card-row no-gutters {{ $block->card_grid->classes() }}">
              <div class="grid-card">
                  <div class="image">
                      <div class="image-wrapper" style="background-image: url({{ $block->card_grid->components[0]->image_src_full }})"></div>
                      <div class="overlay"></div>
                      <div class="image-title">
                        {!! $block->card_grid->components[0]->title !!}
                      </div>
                      <a class="image-link" target="{{ $block->card_grid->components[0]->target }}" href="{{ $block->card_grid->components[0]->url }}">
                        <div class="sr-only">
                            {!! $block->card_grid->components[0]->title !!}
                        </div>
                      </a>
                  </div>
              </div>

              <div class="hero-sidebar">
                  <div class="news-search">
                      <form class="searchbar" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
                          <img src="@asset('images/search.svg')" class="search-icon svg" alt="Search News">
                          <div class="sr-only">Search News</div>
                          <input type="hidden" name="news" value="1" aria-labelledby="Search News">
                          <input type="text" value="" name="s" id="s" placeholder="Search News" aria-labelledby="Search News">
                      </form>
                  </div>
                  <div class="news-tags">
                    <h3>Trending Topics</h3>
                    @php
                      $tags = get_tags([
                        'number' => 10,
                        'orderby' => 'count',
                        'order' => 'DESC',
                      ]);
                    @endphp

                    @foreach ($tags as $tag)
                      @php
                        $tag_clean = str_replace(' ', '', $tag->name);
                        $tag_length = strlen($tag_clean) > 30 ? 1 : 0;
                        $tag_link = get_tag_link( $tag->term_id );
                      @endphp
                      <a href="{!! $tag_link !!}" title="#{{ $tag_clean }}">{!! ($tag_length) ? substr($tag_clean,0,30).'...' : $tag_clean !!}</a>
                    @endforeach

                  </div>
              </div>
          </div>
        @else
          @card_grid(["card_grid" => $block->card_grid]) @endcard_grid
        @endif

        {{-- {{ print_r($block->hiddenTags) }} --}}

        @if ($block->type === 'latest' && is_page( 481 ))
          <div class="news-load-more">
            @php
              global $wp_query;
              $temp = $wp_query;
              $wp_query = null;
              $wp_query = new WP_Query($block->args);
              echo get_the_posts_navigation();
              $wp_query = null;
              $wp_query = $temp;
            @endphp
          </div>
        @endif

    </div>
@endwrapper
