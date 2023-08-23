<div class="row grid-card-row {{ $card_grid->classes() }}">
    @foreach ($card_grid->components as $card)
        @card(["card" => $card]) @endcard
    @endforeach
</div>
