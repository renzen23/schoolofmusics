<div class="image-mask-group">
    @foreach($image_mask_group->components as $image_mask)
        @image_mask(["image_mask" => $image_mask]) @endimage_mask
    @endforeach
</div>