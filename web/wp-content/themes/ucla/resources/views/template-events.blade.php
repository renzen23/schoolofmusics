{{--
  Template Name: Events
--}}

@extends('layouts.' . $event_layout)

@section('content')
  <div id="tribe-events-pg-template">
  	{{ tribe_events_before_html() }}
    @if ($is_event_program)
      @includeIf('partials.content-single-tribe_events-program')
    @else 
      @includeIf('partials.content-single-tribe_events')
    @endif
  	{{ tribe_events_after_html() }}
  </div> <!-- #tribe-events-pg-template -->
@endsection
