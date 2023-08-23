@wrapper(['block' => $block])
    <div class="container">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
        @foreach ($block->location as $key => $location)
            @php
                $slug = sanitize_title($location['name']);
            @endphp    
            <li class="nav-item">
                <a class="nav-link {{ ($key==0) ? 'active' : '' }}" id="{{ $slug }}-tab" data-toggle="tab" href="#{{ $slug }}" role="tab" aria-controls="{{ $slug }}" aria-selected="true">{{ $location['name'] }}</a>
            </li>
        @endforeach
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">

        @foreach ($block->location as $key => $location)
            @php 
                $slug = sanitize_title($location['name']);
            @endphp

            <div class="tab-pane {{ ($key==0) ? 'active' : '' }} embed-responsive embed-responsive-16by9" id="{{ $slug }}" role="tabpanel" aria-labelledby="{{ $slug }}-tab">
                <iframe class="embed-responsive-item" src="{!! $location['url'] !!}" allowfullscreen></iframe>
            </div>
          @endforeach

        </div>
    </div>
@endwrapper