@while(have_rows('sections'))
  @php
    the_row();
    $layout = get_row_layout();
    $class = 'App\\Builder\\BlockModules';
    $array = call_user_func( ['App\Builder\Config', $layout] );
    $block = new $class($array);

    if ( (is_front_page() && $layout == 'hero') || (false && $layout == 'hero' && $block->style == 'image-mask') ) {
    	$layout = 'hero-home';
    }
    
  @endphp

  @includeIf('partials.builder.' . $layout, ['block' => $block])

@endwhile
