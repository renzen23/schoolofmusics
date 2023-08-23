@extends('layouts.app')

@section('content')
  <div class="container">

    <div class="row search-header">
      <div class="col-12">
        {{-- @include('partials.page-header') --}}
        <h1>Search<br><em>Results</em></h1>
      </div>
    </div>

    <div class="row">
      <div class="col-12 col-lg-10 d-flex">
        <form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
          <div class="search-wrapper">
            <input type="text" class="col" value="" name="s" id="s" placeholder="Search UCLA School of Music" />
            <input type="submit" class="col-auto" id="searchsubmit" value="Search" />
            <img src="@asset('images/search.svg')" class="svg">
          </div>
        </form>
      </div>
    </div>

    <div class="row search-count">
      <div class="col-12">
        @php
          global $wp_query;
          // var_dump($wp_query);
          echo $wp_query->found_posts .' results for <span>' . sanitize_text_field($wp_query->query['s']) . '</span>';
        @endphp
      </div>
    </div>

    @if (!have_posts())
      <div class="alert alert-warning">
        {{  __('Sorry, no results were found.', 'sage') }}
      </div>
    @endif

    <div class="row">
      <div class="col-12 search-results">
        @while(have_posts()) @php(the_post())
          @include('partials.content-search')
        @endwhile
      </div>
    </div>

    <div class="row search-load-more">
      <div class="col-12">
        {!! get_the_posts_navigation() !!}
      </div>
    </div>

  </div>
@endsection
