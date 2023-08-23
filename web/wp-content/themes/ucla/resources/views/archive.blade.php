@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-12">
        @include('partials.page-header')
      </div>
    </div>

    <div class="row">
      @php
          $Singleblock = 'App\\Builder\\SingleBlock';
          $templates = [
              "news_grid",
          ];
      @endphp

      @foreach($templates as $template)
          @php
              $block = new $Singleblock($template);
              call_user_func(array($block, 'post'."_".$template));
          @endphp

          @includeIf('partials.builder.'.$template, ['block' => $block])
      @endforeach
    </div>

    <div class="row">
      <div class="col-12">
        {!! get_the_posts_navigation() !!}
      </div>
    </div>
  </div>
@endsection
