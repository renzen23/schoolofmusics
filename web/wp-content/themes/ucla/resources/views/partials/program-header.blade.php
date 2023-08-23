<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TKT368K"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

@foreach(get_field('ribbons', 'option') as $ribbon)
  @if ( $ribbon['display'] == 'show' && in_array(get_the_ID(), $ribbon['display_on']) )
    @include('partials.builder.ribbon')
  @endif
@endforeach

<header class="banner banner__dark-mode">
  <div class="banner-main">
    <div class="container">
      <div class="banner-main-wrapper row shown">
        <div class="brand">
          <a href="{{ home_url('/') }}">
            @if ($header_logo_inverse)
            <img alt="{{ get_bloginfo('name', 'display') }}" src="{{ wp_get_attachment_image_src( $header_logo_inverse['id'], 'full' )[0] }}" class="attachment-full size-full">
            @endif
          </a>
        </div>
        <nav class="nav-primary d-none d-md-flex">
          @if (has_nav_menu('primary_navigation'))
            {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav', 'depth' => 1]) !!}
          @endif
        </nav>

        <nav class="nav-secondary align-items-end d-none d-md-flex">
          @if ( get_field('header_button', 'option') == 'show' )
            <div class="apply-button mr-0">
              <a href="{{ get_field('header_button_link', 'option')['url'] }}" class="cta pill-cta blue"><span>{{ get_field('header_button_link', 'option')['title'] }}</span></a>
            </div>
          @endif
          @if (has_nav_menu('secondary_navigation'))
            {!! wp_nav_menu(['theme_location' => 'secondary_navigation', 'menu_class' => 'nav d-none d-md-flex', 'depth' => 1]) !!}
          @endif
        </nav>
        
        <div class="calendar-button d-none d-md-flex">
            <a href="/calendar/">
                <span>Calendar</span>
                <img src="@asset('images/calendar.svg')" alt="calendar" />
            </a>
        </div>
        <div class="search-button d-none d-md-block">
            <a href="#">
                <span>Search</span>
                <img src="@asset('images/search.svg')" alt="search" />
            </a>
        </div>
      </div>
    </div>
  </div>
  {{-- hide nav on search and ensembles main page --}}
  @if( ! is_search() && ! is_page(484) )
    <div class="banner-local shown d-none d-md-block">
      <div class="container">
        @include('partials.nav')
      </div>
    </div>
  @endif
  <div class="banner-search d-none d-md-block">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-10 d-flex">
          <form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
            <div class="search-wrapper">
              <label for="banner-search-text" class="sr-only">Search UCLA School of Music</label>
              <input type="text" class="col" value="" name="s" id="banner-search-text" placeholder="Search UCLA School of Music" />
              <input type="submit" class="col-auto" id="searchsubmit" value="Search" />
              <img src="@asset('images/search.svg')" class="svg" alt="search">
              <div class="sr-only">Search</div>
            </div>
          </form>
          <div class="search-close col-auto">
            <span></span><span></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>




{{-- @php var_dump($menu); @endphp --}}

{{-- @php wp_nav_menu(
  array(
      'theme_location'=>'primary_navigation',
      'depth'=>3,
      'walker'=>new Selective_Walker() )
  ); @endphp --}}
