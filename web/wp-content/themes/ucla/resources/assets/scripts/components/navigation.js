let $window = $(window);
let windowWidth = $window.width();
let windowPos = $window.scrollTop();
let windowSize = '';
let childNavHeight = '';

/* -------------------------------------------- */
// Media Queries
if (windowWidth > 992) {
    windowSize = 'desktop';
} else if (windowWidth < 768) {
    windowSize = 'mobile';
} else {
    windowSize = 'tablet';
}

/* -------------------------------------------- */
// Window Scroll
$window.scroll(function () {
    windowPos = $window.scrollTop();

    if (windowSize === 'desktop' && windowPos < 100) {
        $('.banner-main-wrapper, .banner-local').addClass('shown');
    } else if (windowSize === 'desktop') {
        $('.banner-main-wrapper, .banner-local').removeClass('shown open');
        $('.banner-local-wrapper').removeClass('sub-1');
    }

    if (windowSize === 'tablet' && windowPos < 100) {
        $('.banner-main-wrapper').addClass('shown');
    } else if (windowSize === 'tablet') {
        $('.banner-main-wrapper').removeClass('shown open');
    }
});

/* -------------------------------------------- */
// Window Resize
$window.resize(function () {
    windowWidth = $window.width();

    if (windowWidth > 992) {
        windowSize = 'desktop';
        if (windowPos < 100) {
            $('.banner-main-wrapper, .banner-local').addClass('shown').removeClass('open');
        }
    } else if (windowWidth < 768) {
        windowSize = 'mobile';
        $('.banner-main-wrapper, .banner-local').removeClass('shown open');
    } else {
        windowSize = 'tablet';
        if (windowPos < 100) {
            $('.banner-main-wrapper').addClass('shown').removeClass('open');
            $('.banner-local').removeClass('shown open');
        }
    }
});

/* -------------------------------------------- */
// Banner
$('.banner-main').hover(
    function () {
        if (windowSize === 'desktop' && windowPos > 100) {
            $('.banner-main-wrapper').addClass('shown');
        }
    },
    function () {
        if (windowSize === 'desktop' && windowPos > 100) {
            $('.banner-main-wrapper').removeClass('shown');
        }
    }
);

$('.banner-local').hover(
    function () {
        if (windowSize === 'desktop' && windowPos > 100) {
            $('.banner-local').addClass('shown');
        }
    },
    function () {
        if ((windowSize === 'desktop' || windowSize === 'tablet') && windowPos > 100) {
            $('.banner-local').removeClass('shown');
            $('.child-nav').removeClass('shown');
            $('.banner-local-wrapper').removeClass('sub-1');
            $('.banner-local-wrapper').css('height', '');
        }
    }
);

$('.nav-bars').click(function () {
  if (windowSize === 'desktop' || windowSize === 'tablet') {
    $('.banner-main-wrapper').toggleClass('shown');
  } else {
    $('.banner-main-wrapper').toggleClass('open');
  }
});

$('.local-dropdown').click(function () {
    $('.banner-local').toggleClass('shown open');
});

$('nav li.has-children > a').click(function (e) {
    e.preventDefault();
    // bannerLocalHeight = $(".banner-local-wrapper").outerHeight();
    $(this).parents('li').children('.child-nav').addClass('shown');
    $('.banner-local-wrapper').addClass('sub-1');
    childNavHeight = $(this).parents('li').children('.child-nav').outerHeight();
    $('.banner-local-wrapper').css('height', childNavHeight + 50 + 'px');
});

$('nav .child-nav li.back a').click(function (e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).parents('.child-nav').removeClass('shown');
    $('.banner-local-wrapper').removeClass('sub-1');
    $('.banner-local-wrapper').css('height', '');
});

// $("nav .nav-title").click( function(e) {
//   e.preventDefault();
// });

$('.search-button, .banner-search .search-close').click(function (e) {
    e.preventDefault();
    $('.banner-search').toggleClass('shown');
    $('#banner-search-text').focus();
});
