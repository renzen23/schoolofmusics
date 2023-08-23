@php
// Photo credit loop (doesn't work properly, can't use post_parent as arg)
// $args = [
//   'post_type' => 'attachment',
//   'posts_per_page' => '20',
//   'post_parent' => get_the_id(),
//   'meta_query' => array(
//     'relation' => 'OR',
//     array(
//       'key' => 'author_name',
//       'compare' => 'EXISTS',
//     ),
//     array(
//       'key' => 'author_name',
//       'value' => '',
//       'compare' => '!=',
//     )
//   )
// ];
// $photo_credit_posts = get_posts($args);
$copyright = get_field('copyright', 'option');
@endphp

<footer class="content-info">
  <div class="container">
    <div class="row">

      <div class="col-12 col-sm-6 col-md-4">
        <a class="footer-brand" href="{{ home_url('/') }}">
            <img alt="{{ get_bloginfo('name', 'display') }}" src="{{ wp_get_attachment_image_src( $footer_logo['id'], 'full' )[0] }}" class="attachment-full size-full">
        </a>
        <div class="footer-address">
          {!! $address !!}
        </div>
      </div>
      <nav class="nav-footer col-12 col-sm-6 col-md-4">
        @if (has_nav_menu('footer_navigation'))
          {!! wp_nav_menu(['theme_location' => 'footer_navigation', 'menu_class' => 'nav']) !!}
        @endif
      </nav>

      <div class="col-12 col-sm-6 col-md-4">

        <div class="footer-external d-flex justify-content-lg-end">
          @if( $external_links )
            @foreach( $external_links as $link )
              <a href="{!! $link['url'] !!}" class="icon">
                @if( $link['external_link_icon'] == 'link-icon' )
                  <img src="@asset('images/link.svg')" class="svg" alt="{{ $link['url'] }}">
                  <div class="sr-only">{!! $link['url'] !!}</div>
                @else
                  <i class="fab fa-{!! $link['external_link_icon'] !!}"></i>
                  <div class="sr-only">{!! $link['external_link_icon']  !!}</div>
                @endif
              </a>
            @endforeach
          @endif
        </div>

        {{-- Photo credit --}}
        {{-- @if( $photo_credit_posts )
          <div class="footer-photo-credit d-flex justify-content-lg-end">
            <div class="photo-credit-button">
              <img src="@asset('images/camera.svg')" alt=""> Photo Credits
            </div>
            <div class="photo-credits">
              <div class="photo-credit-list">
                @foreach( $photo_credit_posts as $credit )
                  @php
                    $name = get_field('author_name', $credit->ID);
                    $link = get_field('author_website', $credit->ID);
                    $link_clean = str_replace(
                      array('http://', 'https://'),
                      array('', ''),
                      $link
                    );
                  @endphp
                  <div class="credit-card d-flex align-items-center">
                    {!! wp_get_attachment_image( $credit->ID, 'thumbnail' ) !!}
                    <p>
                      {!! $name !!}
                      @if($link) <a href="{!! $link !!}" target="_blank">{!! $link_clean !!}</a> @endif
                    </p>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endif --}}

        <div class="footer-copyright">
          <p>&copy; <?php echo date("Y"); ?> {!! $copyright !!}</p>
          <p>Site Design by <a href="http://kley.co/" target="_blank">Kley, Inc</a>.</p>
        </div>

      </div>

    </div>
  </div>
</footer>


@php
    wp_reset_query();
@endphp
