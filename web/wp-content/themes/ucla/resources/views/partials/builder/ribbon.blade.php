<script>
	jQuery(document).ready(function($){
		$('.close-icon').click(function(e){
			e.preventDefault();
			$('.ribbon').fadeOut();
		});
	});
</script>

<div class="ribbon text-center">
	<a href="#" class="close-icon cta circle-cta">
	    <div class="icon bg-white">
			<div style="font-size:32px;">&times;</div>
	    </div>
	</a>
	<div class="container">
		<div class="row">
			<div class="col-md-8 mx-auto">
				<strong>{{ $ribbon['title']}}</strong>
				@if ( $ribbon['description'] != ''  )
					<p>{!! nl2br($ribbon['description']) !!}</p>
				@endif
				@if ( isset($ribbon['ribbon_link']) && is_array($ribbon['ribbon_link'])  )
					<a href="{{ $ribbon['ribbon_link']['url']}}" class="cta circle-cta">
			            <div class="icon bg-white">
			                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 13.346 11.933" class="svg replaced-svg"><defs><style>.a,.b{fill:none;stroke:#fff;stroke-linecap:round;}.b{stroke-linejoin:round;}</style></defs><g transform="translate(-8.401 -5.047)"><path class="a" d="M5.912,0h11.7" transform="translate(2.989 11.014)"></path><path class="b" d="M12,5l5.467,5.467L12,15.933" transform="translate(3.78 0.547)"></path></g></svg>
			                <div class="sr-only">{{ $ribbon['ribbon_link']['title']}}</div>
			            </div>
				        <span class="text-white">{{ $ribbon['ribbon_link']['title']}}</span>
				    </a>
				@endif
			</div>
		</div>
		
	</div>
	
	
</div>