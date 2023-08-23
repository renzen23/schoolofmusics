@php
  $authors = get_field('author');
  $tags = get_the_tags();
  $hero = get_field('options');
  $alternate_hero = get_field('alternate_hero');
  if($hero == 'alternate') {
    $caption = $alternate_hero['caption'] ?: null;
  }
  $caption = get_the_post_thumbnail_caption();
  $url = home_url() ."/?p=". get_the_ID();
@endphp
<article {{ post_class() }}>

  <header>

    @if( has_post_thumbnail() && $hero != 'hide' )
      <div class="featured-image">{!! $hero == 'alternate' ? wp_get_attachment_image($alternate_hero['id'], 'large') : the_post_thumbnail() !!}</div>
      @if( $caption )
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-12 col-sm-11 col-md-10 col-lg-8 d-flex justify-content-end">
              <div class="caption">{!! $caption !!}</div>
            </div>
          </div>
        </div>
      @endif
    @endif

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-sm-11 col-md-10 col-lg-8">
          <h1 class="entry-title">{!! get_the_title() !!}</h1>
          <p class="byline author vcard">
          @if(isset($authors))
            @foreach($authors as $author)
              {!! $author->post_title !!}
            @endforeach
          @endif
          </p>
          <time class="updated" datetime="{{ get_post_time('c', true) }}">{{ get_the_date() }}</time> •
          {!! do_shortcode('[rt_reading_time label="" postfix="min read"]') !!}
          <div class="share-and-tags">
            <div class="tw-share-button">
              <a target="_blank" href="https://twitter.com/share?text={!! the_title() !!}&url={{ $url }}" class="twitter-share-button" data-show-count="false">
                <i class="fab fa-twitter"></i>
                <span class="sr-only">twitter</span>
              </a>
            </div>
            <div class="fb-share-button" data-href="{{ the_permalink() }}" data-layout="button" data-size="small" data-mobile-iframe="true">
              <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}" class="fb-xfbml-parse-ignore">
                <i class="fab fa-facebook-f"></i>
                <span class="sr-only">facebook</span>
              </a>
            </div>
            @if($tags)
              @foreach($tags as $tag)
                @php
                  $tag_clean = str_replace(' ', '', $tag->name);
                @endphp
                <div class="tag">
                  <a href="{{ get_tag_link($tag->term_id) }}">{!! $tag_clean !!}</a>
                </div>
              @endforeach
            @endif
          </div>
        </div>
      </div>
    </div>
  </header>

  <div class="container">
    <div class="entry-content row justify-content-center">
      <div class="col-12 col-sm-11 col-md-10 col-lg-8">
        {!! the_content() !!}
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row justify-content-center">
      <div class="share-and-tags footer col-12 col-sm-10 col-md-8">
        <div class="tw-share-button">
          <a target="_blank" href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-show-count="false">
            <i class="fab fa-twitter"></i>
            <span class="sr-only">twitter</span>
          </a>
        </div>
        <div class="fb-share-button" data-href="https://developers.facebook.com/docs/plugins/" data-layout="button" data-size="small" data-mobile-iframe="true">
          <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">
            <i class="fab fa-facebook-f"></i>
            <span class="sr-only">facebook</span>
          </a>
        </div>
        @if($tags)
          @foreach($tags as $tag)
            @php
              $tag_clean = str_replace(' ', '', $tag->name);
            @endphp
            <div class="tag">
              <a href="{{ get_tag_link($tag->term_id) }}">{!! $tag_clean !!}</a>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </div>

</article>

{{-- @php
    $class = 'App\\Builder\\SingleBlock';
    $block = new $class("news-grid");
    $block->news_related_news();
@endphp
@includeIf('partials.builder.news_grid', ['block' => $block]) --}}
