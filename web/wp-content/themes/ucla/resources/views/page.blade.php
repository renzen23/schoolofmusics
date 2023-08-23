@php
  global $post;
@endphp

@extends('layouts.app')

@section('content')

  @if ( !post_password_required($post) )

      @while(have_posts())
        @php
            the_post();
        @endphp
        @if( have_rows( 'sections' ) )
          @include('partials.content-builder')
        @else
          @include('partials.page-header')
          @include('partials.content-page')
        @endif
      @endwhile

  @else

    {!! get_the_password_form() !!}

  @endif

@endsection
