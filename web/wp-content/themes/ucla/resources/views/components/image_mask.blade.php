<div class="image-mask"
    data-mask-id="{{ $image_mask->mask_id }}"
    data-image-src="{{ $image_mask->image_src }}"
    data-desktop-offset="{{ isset($image_mask->desktop_offset) ? $image_mask->desktop_offset : '' }}"
    data-mobile-offset="{{ isset($image_mask->mobile_offset) ? $image_mask->mobile_offset : '' }}"
    data-desktop-svg="{{ isset($image_mask->desktop_svg) ? $image_mask->desktop_svg : '' }}"
    data-mobile-svg="{{ isset($image_mask->mobile_svg) ? $image_mask->mobile_svg : '' }}"
    data-left-svg="{{ isset($image_mask->left_svg) ? $image_mask->left_svg : '' }}"
    data-right-svg="{{ isset($image_mask->right_svg) ? $image_mask->right_svg : '' }}"
    data-image-caption="{{ isset($image_mask->image_caption) ? $image_mask->image_caption : '' }}">

    @php
        $image_svgs = [
            // for hero image masks
            "desktop" => isset($image_mask->desktop_svg) ? $image_mask->desktop_svg : '',
            "mobile" => isset($image_mask->mobile_svg) ? $image_mask->mobile_svg : '',
            // for custom image masks
            "left" => isset($image_mask->left_svg) ? $image_mask->left_svg : '',
            "right" => isset($image_mask->right_svg) ? $image_mask->right_svg : '',
        ];
    @endphp
    @foreach ($image_svgs as $label => $src)
        @if ($src)
            <img id="{{ $image_mask->mask_id."-".$label."-svg" }}" data-src="{{ $src }}" alt="" class="{{ $label."-svg" }} clip-path d-none hidden">
            <svg class="clip-paths {{ $label }}-clip-paths" viewBox="0 0 1340 640">
                <defs>
                    {{-- clip paths appended here with javascript --}}
                </defs>
            </svg>
        @endif
    @endforeach

    <svg class="image-groups" viewBox="0 0 1340 640" aria-labelledby="{{ $image_mask->svg_title }}" role="img">
        <title id="{{ $image_mask->mask_id."-svg-title" }}">{{ $image_mask->svg_title }}</title>
        @if ($image_mask->svg_description)
            <desc id="{{ $image_mask->mask_id."-svg-description" }}">{{ $image_mask->svg_description }}</desc>
        @endif
        {{-- image groups appended here with javascript --}}
    </svg>

    @if (isset($image_mask->image_caption) ? $image_mask->image_caption : '')
        <svg class="captions a-fade-in" viewBox="0 0 1340 640">
            <title id="{{ $image_mask->mask_id."-svg-caption" }}">{{ $image_mask->image_caption }}</title>
            {{-- text caption added here with javascript --}}
        </svg>
    @endif

</div>