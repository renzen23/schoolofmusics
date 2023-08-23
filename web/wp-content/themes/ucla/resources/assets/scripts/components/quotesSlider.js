/* -------------------------------------------- */
// QUOTES SLIDER
// Looped incase there are multiple quotes on a page

$('section.b-quotes').each(function () {
    let $block = $(this);
    let id = $block.attr('id');

    // image-mask slider
    $(this).find('.image-mask-group').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        dots: false,
        fade: true,
        draggable: false,
        asNavFor: '.b-quotes#' + id + ' .quote-wrapper',
    });

    // quote nav
    $(this).find('.quote-wrapper').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        asNavFor: '.b-quotes#' + id + ' .image-mask-group',
        prevArrow: $block.find('.slick-arrows .prev-arrow'),
        nextArrow: $block.find('.slick-arrows .next-arrow'),
        appendDots: $block.find('.slick-dots-wrapper'),
        dots: true,
        draggable: false,
        speed: 500,
        fade: true,
        autoplay: true,
        autoplaySpeed: 5000,
        cssEase: 'linear',
    });
});