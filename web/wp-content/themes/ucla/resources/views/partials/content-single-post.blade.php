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
  $postID = get_the_ID();
@endphp
<article {{ post_class() }}>

  <header>
    <section id="block-0" class="b-hero background-image">
      <div class="container">
        <div class="row hero-row">
          <div class="col-lg-4 mt-md-4">
            <div class="header">
              <h1>{!! get_the_title() !!}</h1>
              <p class="byline author vcard">
            @if(isset($authors))
              @foreach($authors as $author)
                {!! $author->post_title !!}
              @endforeach
            @endif
            </p>
            <time class="updated text-white" datetime="{{ get_post_time('c', true) }}">{{ get_the_date() }}</time> •
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
            @if( ! is_search() && ! is_page(484) )
              <div class="banner-nav">
                @include('partials.nav')
              </div>
            @endif
          </div>
          <div class="col-lg-8 ml-auto mb-3 mb-md-0">
            {!! $hero == 'alternate' ? wp_get_attachment_image($alternate_hero['id'], 'large',0,['class'=>'img-fluid']) : the_post_thumbnail('large',['class'=>'img-fluid']) !!}
            @if( $caption )
              <div class="caption text-left">{!! $caption !!}</div>
            @endif
          </div>
        </div>
      </div>
    </section>

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

@if ($tags)
@php
  //only show this if is a hidden tag

  $block = new App\Builder\SingleBlock('news_grid');
  $block->hidden_news_grid($tags,$postID);
  $block->news_hero = false;
  $tagIDs = array_column($tags,'term_id');
  $showRelated = array_intersect($block->hiddenTags,$tagIDs);

@endphp
@includeWhen($showRelated,'partials.builder.news_grid', ['block' => $block])
@endif
