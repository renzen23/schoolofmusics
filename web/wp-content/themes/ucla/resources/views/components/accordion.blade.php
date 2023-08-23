<div class="accordion cols {{ $accordion->classes() }}">

    <div class="header" id="{{ $accordion->trigger_id }}" data-toggle="collapse" data-target="#{{ $accordion->collapse_id }}" aria-expanded="{{ $accordion->index_first ? 'true' : 'false' }}" >
        @if ($accordion->year)
            <div class="year">
                {!! $accordion->year !!}
            </div>
        @endif
        @if ($accordion->title)
            <div class="title">
                {!! $accordion->title !!}
            </div>
        @endif
        {{-- @if ($accordion->author)
            <div class="author">
                {!! $accordion->author !!}
            </div>
        @endif --}}
        <div class="icon">
            <img src="@asset('images/chevron.svg')" class="svg" />
        </div>
    </div>

    <div id="{{ $accordion->collapse_id }}" class="content collapse {{ $accordion->index_first ? "show" : null }}" aria-labelledby="{{ $accordion->trigger_id }}">
        <div class="row no-gutters inner-wrapper">
            @if ($accordion->text)
                <div class="text">
                    {!! $accordion->text !!}
                </div>
            @endif
            @if ($accordion->year || $accordion->publication || $accordion->categories || $accordion->authors || $accordion->notes || $accordion->cta )
                <div class="details">
                    @if ($accordion->publication)
                        <div class="publication">
                            {!! $accordion->publication !!}
                        </div>
                    @endif
                    @if ($accordion->categories)
                        <div class="categories">
                            {!! $accordion->categories !!}
                        </div>
                    @endif
                    @if ($accordion->date)
                        <div class="date">
                            {!! $accordion->date !!}
                        </div>
                    @endif
                    @if ($accordion->authors)
                        <div class="authors">
                            {!! $accordion->authors !!}
                        </div>
                    @endif
                    @if ($accordion->notes)
                        <div class="notes">
                            {!! $accordion->notes !!}
                        </div>
                    @endif
                    @if ($accordion->cta)
                        @cta(["cta" => $accordion->cta]) @endcta
                    @endif
                </div>
            @endif
        </div>
    </div>

</div>