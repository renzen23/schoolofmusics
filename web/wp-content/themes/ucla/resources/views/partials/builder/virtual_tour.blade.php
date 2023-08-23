@wrapper(['block' => $block])
    <a href="{{ $block->url }}" target="_blank">
        <div class="container">
            <div class="row virtual-tour-row">
                <img src="@asset('images/play.svg')" class="play-button" alt="Continue to Virtual Tour">
                @if ($block->header)
                    <div class="header cols">
                        {!! $block->header !!}
                    </div>
                @endif
            </div>
        </div>
    </a>
@endwrapper
