@if (($block->featured_events && count($block->events) >= $block->events_by_categories_min) || !$block->featured_events)
@wrapper(['block' => $block])
<div class="container {{ $block->featured_events ? 'featured-events' : '' }} ">
	@if($block->title)
	<div class="row">
		<div class="col-12">
			<h2 class="title text-primary font-weight-normal">{{ $block->title }}</h2>
		</div>
	</div>
	@endif
	<div class="position-relative">
		<div class="slick-arrow slick-prev-arrow {{ count($block->events) <= 3 ? 'd-lg-none' : '' }}">
			<img src="@asset('images/arrow.svg')" alt="previous arrow">
		</div>
		<div class="events-slider {{ $block->featured_events ? 'featured-events-slider' : '' }}">
    @if ($block->events)
			@foreach($block->events as $event)
			@if($block->featured_events)
			<div class="event-card position-relative">
				<div class="block-foreground">
					<div class="wrapper d-flex align-items-end h-100">
						<div class="event-date">
							<span class="date-month">{{ $event->date->month }}</span>
							<span class="date-number">{{ $event->date->date }}</span>
							<span class="date-text">{{ $event->date->day }}</span>
						</div>
						<a class="text-white event-info" href="{{ $event->link }}">
							<p class="category text-uppercase text-white">{{ $event->category }}</p>
							<h3 class="event-card-title">
								{{ $event->title }}
							</h3>
						</a>
					</div>
				</div>
				<div class="block-background">
					<div class="image-container">
						<img src="{{ $event->image }}" alt="{{ $event->title }}" />
					</div>
				</div>
			</div>
			@else
			<div class="event-card">
				<a href="{{ $event->link }}">
					<div class="image-container">
						<img src="{{ $event->image }}" alt="{{ $event->title }}" />
					</div>
				</a>
				<div class="position-relative">
					<p class="category text-uppercase text-primary">{{ $event->category }}</p>
					<h3 class="event-card-title">
						<a class="text-darkgrey" href="{{ $event->link }}">
							{{ $event->title }}
						</a>
					</h3>
					<div class="event-date">
						<span class="date-month">{{ $event->date->month }}</span>
						<span class="date-number">{{ $event->date->date }}</span>
						<span class="date-text">{{ $event->date->day }}</span>
					</div>
				</div>
			</div>
			@endif
			@endforeach
      @endif
		</div>
		<div class="slick-arrow slick-next-arrow {{ count($block->events) <= 3 ? 'd-lg-none' : '' }}">
			<img src="@asset('images/arrow.svg')" alt="next arrow">
		</div>
	</div>
</div>
@endwrapper
@endif
