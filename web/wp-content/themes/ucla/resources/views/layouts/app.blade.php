<!doctype html>
<html {{ language_attributes() }}>
  @include('partials.head')
  <body {{ body_class("preload") }}>
    {{ do_action('get_header') }}
    @include('partials.header')
    <div class="wrap {{ isset(App\ucla_get_menu_item_ID('primary_navigation')['items']) ? "has-submenu" : "" }}" role="document">
      <div class="content">
        <main class="main">
          @yield('content')
        </main>
        @if (App\display_sidebar())
          <aside class="sidebar">
            @include('partials.sidebar')
          </aside>
        @endif
      </div>
    </div>
    {{ do_action('get_footer') }}
    @include('partials.footer')
    {{ wp_footer() }}
  </body>
</html>
