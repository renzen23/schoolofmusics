<div class="container">
    
    <div class="event-program__hero">
        {!! wp_get_attachment_image($program['image']['ID'], 'large', 0, ['class'=>'img-fluid w-100']) !!}
    </div><!-- .event-program__hero -->
    
    <div class="event-program__intro">
        <div class="row justify-content-md-between">
            <div class="col-md-4">
                @if ($program['title'])
                <div class="d-flex flex-column-reverse">
                    <h1>{!! $program['title'] !!}</h1>
                </div>
                @endif
            </div>
            <div class="col-md-6">
                @if ($program['intro'])
                {!! $program['intro'] !!}
                @endif
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            @if ($program['performers'])
            <h2>{{$program['performers_title'] ?: 'Performers'}}</h2>
            
            <div class="event-program__performers">
                @foreach ($program['performers'] as $performer)
                <div class="event-program__performer text-center text-md-left">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            @if ($performer['image'])
                            <div class="event-program__performer-image-container">
                                <div class="event-program__performer-image">
                                    {!! wp_get_attachment_image($performer['image']['ID'], 'medium') !!}
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h3>{!! $performer['name'] !!}</h3>
                            
                            @if ($performer['intro'])
                            <span class="event-program__performer-tagline">{!! $performer['intro'] !!}</span>
                            @endif
                            
                            @if ($performer['bio'])
                            <a href="#" class="d-none d-md-block text-uppercase event-program__show-bio" data-toggle="collapse" data-target="#performer-{{ $loop->index }}"><span>{{$performer['bio_title'] ?: 'See Bio'}}</span></a>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            @if ($performer['bio'])
                            <div class="collapse d-print-block" id="performer-{{ $loop->index }}">
                                {!! $performer['bio'] !!}
                            </div>
                            
                            <a href="#" class="d-md-none text-uppercase event-program__show-bio" data-toggle="collapse" data-target="#performer-{{ $loop->index }}"><span>{{$performer['bio_title'] ?: 'See Bio'}}</span></a>
                            @endif
                        </div>
                    </div>
                    
                </div>
                @endforeach
            </div>
            @endif
        </div><!-- .col-md-6 -->
        <div class="col-md-6">
            @if ($program['repertoire'])
            <div class="event-program__card">
                <h2>Repertoire</h2>
                <div class="event-program__repertoire">
                    {!! $program['repertoire'] !!}
                </div>
            </div>
            @endif
            
            @if ($program['about'])
            <div class="event-program__card">
                <h2>Donor Acknowledgement</h2>
                {!! $program['about'] !!}
            </div>
            @endif
            
            @if ($program['notes'])
            <div class="event-program__card">
                <h2>Program Notes</h2>
                {!! $program['notes'] !!}
            </div>
            @endif
        </div><!-- .col-md-6 -->
    </div><!-- .row -->
</div><!-- .container -->