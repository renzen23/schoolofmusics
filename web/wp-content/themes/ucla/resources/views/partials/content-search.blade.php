@php
  $post_type = get_post_type();
  $post_type_object = get_post_type_object( $post_type );
  $post_type_singular = $post_type_object->labels->singular_name;
@endphp

<article {!! post_class() !!}>
  <header>
    <h3 class="entry-type">{!! $post_type_singular !!}</h3>
    <h2 class="entry-title"><a href="{{ get_permalink() }}">{!! get_the_title() !!}</a></h2>
    {{-- @if (get_post_type() === 'post')
      @include('partials/entry-meta')
    @endif --}}
  </header>
  <div class="entry-summary">
    {!! the_excerpt() !!}
  </div>
</article>
