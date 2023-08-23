@php
    $Singleblock = 'App\\Builder\\SingleBlock';
    $templates = [
        "profile",
    ];
@endphp

@foreach($templates as $template)
    @php
        $block = new $Singleblock($template);
        call_user_func(array($block, get_post_type()."_".$template));
    @endphp

    @includeIf('partials.builder.'.$template, ['block' => $block])
@endforeach

@if( have_rows( 'sections' ) )
  @include('partials.content-builder')
@endif

@php
    $templates = [
        "video",
        "research_accordion",
        "news_grid",
        "related_press",
        "people_grid",
        "degrees_grid",
    ];
@endphp

@foreach($templates as $template)
    @php
        $block = new $Singleblock($template);
        call_user_func(array($block, get_post_type()."_".$template));
    @endphp

    @includeIf('partials.builder.'.$template, ['block' => $block])
@endforeach
