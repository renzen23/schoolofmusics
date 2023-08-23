@wrapper(['block' => $block])
    <div class="container">

        @section_header(["section_header" => $block->section_header]) @endsection_header

        <div class="row no-gutters application-journey-row">
            @foreach ($block->steps as $step)
                <div class="step">
                    <div class="content">
                        <div class="number">{{ $loop->index + 1 }}</div>
                        <div class="title">{{ $step->title }}</div>
                        @if ($step->text)
                            <div class="text">{{ $step->text }}</div>
                        @endif
                        @if ($step->cta->url)
                            @cta(["cta" => $step->cta]) @endcta
                        @endif
                        <img class="journey-icon a-fade-in" src="{{ $step->icon_src }}" class="svg" alt="{{ $step->title }}" />
                        <div class="sr-only">{!! $step->title !!}</div>
                    </div>
                    <div class="curved-line">
                        <span></span>
                        <div class="dot"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endwrapper