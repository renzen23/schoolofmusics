
let $window = $(window);
let tmax_linear = Power0.easeNone;
let tmax_easeOut = Power4.easeOut;
let controller = new ScrollMagic.Controller();
let mobile = $window.width() < 768;


$(window).load(function() {
    controllerSetup();
})

var resizeTimer;
$window.resize(function () {

    mobile = $window.width() < 768;

    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function () {
        controllerSetup();
    }, 250);
});

function controllerSetup() {
    controller.enabled(!mobile);
    if (mobile) {
        $('section.b-navigation-carousel .image-mask-group').removeClass("scroll");
    }



$('section.b-navigation-carousel').each(function () {
    let $section = $(this);
    let $row = $section.find(".navigation-carousel-row");
    let $imageMaskGroup = $section.find(".image-mask-group");
    let $content = $section.find('.content');
    let $li = $content.find("ul li");


    /* -------------------------------------------- */
    // UP DOWN SCROLLING TWEEN
    // Animate content on scroll up and down
    let s_onScroll = new ScrollMagic.Scene({
        triggerElement: $section[0],
        triggerHook: 0,
        duration: "50%",
        reverse: true,
    })
    .setTween(tl_navigationCarousel($section))
    .addTo(controller);

    function tl_navigationCarousel($section) {
        let tl = new TimelineMax();
        tl.add(TweenMax.to(
            $imageMaskGroup, 1, {
                className: "+=scroll",
                ease: tmax_linear,
            }), 0);
        return tl;
    }


    /* -------------------------------------------- */
    // ON MOUSEOVER CHANGE SLIDE
    // Only on desktop
    $li.eq(0).addClass("active");
    $imageMaskGroup.find('.image-mask').eq(0).addClass('active');

    $li.each(function (index) {
        let $this = $(this);
        let $imageMask = $section.find('.image-mask').eq(index);

        $this.mouseover(function () {
            if (!mobile) {
                if (!$this.hasClass("active")) {
                    $section.find(".active").removeClass("active");
                    $this.addClass("active");
                    $imageMask.addClass("active");
                }
            }
        });
    });

});


}