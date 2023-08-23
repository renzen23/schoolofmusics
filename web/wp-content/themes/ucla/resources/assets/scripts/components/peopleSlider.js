/* -------------------------------------------- */
// PEOPLE GRID SLIDER
// Wait for load to set card height properly
// There should be solution a to achieve the same result without waiting for window load.

$('section.b-people-grid.grid.slider').each(function () {
    let $block = $(this);
    let $header_row = $block.find('.section-header-row');
    let $grid_card_row = $block.find('.grid-card-row');
    $grid_card_row.slick({
        infinite: false,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 3,
        dots: true,
        centerMode: false,
        prevArrow: $header_row.find('.slick-arrows .prev-arrow'),
        nextArrow: $header_row.find('.slick-arrows .next-arrow'),
        appendDots: $header_row.find('.slick-dots-wrapper'),
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                },
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                },
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    variableWidth: true,
                    centerMode: true,
                },
            },
        ],
    });
});

/* -------------------------------------------- */
// PEOPLE TEAM SLIDER
// Single full width slider instead of grid

$('section.b-people-grid.team.feature.slider').each(function () {
    let $block = $(this);
    let $header_row = $block.find('.section-header-row');
    let $grid_card_row = $block.find('.grid-card-row');
    $grid_card_row.slick({
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        fade: true,
        autoplay: true,
        autoplaySpeed: 5000,
        cssEase: 'linear',
        prevArrow: $header_row.find('.slick-arrows .prev-arrow'),
        nextArrow: $header_row.find('.slick-arrows .next-arrow'),
        appendDots: $header_row.find('.slick-dots-wrapper'),
    });
});