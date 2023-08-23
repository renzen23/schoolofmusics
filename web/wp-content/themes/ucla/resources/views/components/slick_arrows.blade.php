@php use App\Builder\Component; @endphp

<div class="slick-arrows">
    @cta(["cta" => new Component(["classes" => ["circle-cta", "yellow", "small", "flip-icon", "prev-arrow"] ])]) @endcta
    @cta(["cta" => new Component(["classes" => ["circle-cta", "yellow", "small", "next-arrow"] ])]) @endcta
</div>