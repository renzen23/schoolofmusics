/* -------------------------------------------- */
// ANIMATE IN first image mask in image mask group

let tmax_linear = Power0.easeNone;
let tmax_easeOut = Power4.easeOut;
let controller = new ScrollMagic.Controller();

/* -------------------------------------------- */
// SCROLL MAGIC SECTION TRIGGER
// When section enters into frame animate in content.

$('section').each(function () {
    let $section = $(this);
    let s_loadIn = new ScrollMagic.Scene({
        triggerElement: $section[0],
        triggerHook: 1,
        reverse: false,
    })
    .on('enter', function () {
        animateInClasses($section);
    })
    .addTo(controller);
});

/* -------------------------------------------- */

function animateInClasses($section) {

    // Prevent content from animating twice.
    if (!$section.hasClass('animated')) {
        $section.addClass('animated');

        $section.find(".a-fade-in-up").each(function (i) {
            TweenMax.fromTo(
                $(this), 1, {
                    autoAlpha: 0,
                    y: 10,
                }, {
                    autoAlpha: 1,
                    y: 0,
                    ease: tmax_easeOut,
                    delay: i * 0.2,
                }
            );
        });

        $section.find(".a-fade-in").each(function (i) {
            TweenMax.fromTo(
                $(this), 1, {
                    autoAlpha: 0,
                }, {
                    autoAlpha: 1,
                    ease: tmax_easeOut,
                    delay: i * 0.2,
                }
            );
        });

        if ($section.hasClass('b-application-journey')) {
            $section.find(".curved-line span").each(function () {
                TweenMax.fromTo(
                    $(this), 1, {
                        width: '100%',
                    }, {
                        width: '0%',
                        ease: tmax_linear,
                    }
                );
            });
        }

        if ($section.hasClass('blue-bg') || $section.hasClass('yellow-bg')) {
            $section.addClass('show-gradient');
        }
    }
}