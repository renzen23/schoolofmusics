
@php 
    $menu = App\ucla_get_menu_item_ID('primary_navigation');
@endphp

@if (isset($menu['items']))
    @foreach($menu['items'] as $key => $value)
        @if ( $menu['items'][$key]['classes'] && in_array('hide', $menu['items'][$key]['classes']) )
            @php 
                unset($menu['items'][$key]);
            @endphp
        @endif
    @endforeach
@endif

@if (isset($menu['items']))
    <div class="banner-local-wrapper">
        <div class="inner-container">
            <div class="banner-local-top">
                <div class="crumbs">
                    <a href="/">Home</a>
                    @if ($menu['ancestors'])
                        @foreach ($menu['ancestors'] as $ancestor)
                            <span>/</span><a href="{{ $ancestor['url'] }}">{!! $ancestor['title'] !!}</a>
                        @endforeach
                    @endif
                </div>
            </div>

            @if ($menu['parent'])
                <div class="banner-local-middle">
                    <nav class="d-flex justify-content-between">
                        <ul class="nav">
                            <li class="parent active">
                                <a href="{{ $menu['parent']['url'] }}" class=""><span>{!! $menu['parent']['title'] !!}</span></a>
                            </li>
                        </ul>
                    </nav>
                    <div class="local-dropdown">
                        <img src="@asset('images/chevron.svg')" alt="dropdown" />
                    </div>
                </div>
            @endif

            <div class="banner-local-bottom">
                <nav class="nav-local">
                    <ul class="nav">
                        @foreach ($menu['items'] as $item)
                            @php
                                $classes = implode(' ',$item['classes']);
                                $classes .= ($item['ID'] == $post->ID) ? ' active' : '';
                                $classes .= (array_key_exists('children',$item) && $item['url']=='#') ? ' has-children' : '';
                                $target = $item['target'] ? "target='".$item['target']."'" : '';
                            @endphp

                            <li class="{{ $classes }}">
                                <a href="{{ $item['url'] }}" {!! $target !!}>{!! $item['title'] !!}</a>
                                @if (array_key_exists('children',$item) && $item['url']=='#')
                                    <ul class="child-nav">
                                        @if ($menu['parent']) 
                                            <li class="back">
                                                <a href>{!! $menu['parent']['title'] !!}</a>
                                            </li> 
                                        @endif
                                        <li class="parent">
                                            <a href="">{!! $item['title'] !!}</a>
                                        </li>
                                        @foreach ($item['children'] as $child)
                                            <li>
                                                <a href="{{ $child['url'] }}" class="{{ join( ' ', $child['classes'] ) }}">{!! $child['title'] !!}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>

                        @endforeach

                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endif