@if (!empty($accordion_group))
	<div class="row no-gutters accordion-group">
		@foreach( $accordion_group->components as $accordion )
			@accordion(["accordion" => $accordion]) @endaccordion
		@endforeach
	</div>
@endif