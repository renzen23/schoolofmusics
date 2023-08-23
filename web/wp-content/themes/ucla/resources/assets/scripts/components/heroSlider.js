let tmax_easeOut = Power4.easeOut;

// HERO IMAGE MASK RESIZE (DESKTOP MOBILE)
// Update hero image mask for resize to desktop/mobile
// Reset image position and clipping path
/* Window delayed resize */
$(window).load(function() {
    updateHeroMask();
    HeroSlider();
})

var resizeTimer;
$(window).resize(function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function () {
        updateHeroMask();
    }, 250);
});

function updateHeroMask() {
    $('section.b-hero.image-mask.hero-mask').each(function () {
        $(this).find('.image-mask').each(function () {
            let $imageMask = $(this);
            let maskId = $imageMask.data('mask-id');
            let imageSrc = $imageMask.data('image-src');
            let desktopOffset = $imageMask.data('desktop-offset');
            let mobileOffset = $imageMask.data('mobile-offset');

            let isMobile = $(window).width() < 992;
            let offset = isMobile ? mobileOffset : desktopOffset;
            let $groups = $imageMask.find('.image-groups');
            let $image = $imageMask.find('.image-groups g image');
            let $group = $imageMask.find('.image-groups g');

            $imageMask.find('.image-groups g').each(function (i) {
                let imageId = maskId + '-index-' + i + (isMobile ? '-mobile' : '-desktop');
                $(this).css('clip-path', 'url(#' + imageId + ')');
                $(this).find('image').css('transform', 'translateX(' + offset / 10 + 'rem' + ')');
            });
        });
    });
}

/* -------------------------------------------- */
// HERO SLIDER
// Uses slick slider, custom animation added before and after change with TweenMax
function HeroSlider() {
$('section.b-hero.home-slider').each(function () {
    let $imageMaskGroup = $(this).find('.image-mask-group');

    $imageMaskGroup.slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: false,
        draggable: false,
        arrows: false,
        speed: 500,
        fade: true,
        autoplay: true,
        focusOnSelect: false,
        pauseOnFocus: false,
        pauseOnHover: false,
        autoplaySpeed: 6000,
        cssEase: 'linear',
    })
    .on('beforeChange', function (event, slick, currentSlide, nextSlide) {

        let $imageMask = $imageMaskGroup.find('.image-mask').eq(currentSlide);
        let $group = $imageMask.find('.image-groups g');

        $group.each(function (i) {
            let k = i;
            if ((k + 1) % 3 == 0) {
                k = k - 0.6;
            }

            TweenMax.fromTo(
                $(this), 0.5, {
                    autoAlpha: 1,
                    x: 0,
                }, {
                    autoAlpha: 0,
                    x: 15,
                    ease: tmax_easeOut,
                    delay: k * 0.2,
                }
            );
        });
    })
    .on('afterChange', function (event, slick, currentSlide, nextSlide) {
        let $imageMask = $imageMaskGroup.find('.image-mask').eq(currentSlide);
        let $group = $imageMask.find('.image-groups g');

        $($group.get().reverse()).each(function (i) {
            let k = i;
            if ((k + 1) % 3 == 0) {
                k = k - 0.6;
            }
            TweenMax.fromTo(
                $(this), 1, {
                    autoAlpha: 0,
                    x: 15,
                }, {
                    autoAlpha: 1,
                    x: 0,
                    ease: tmax_easeOut,
                    delay: k * 0.2,
                }
            );
        });
    });
})
}