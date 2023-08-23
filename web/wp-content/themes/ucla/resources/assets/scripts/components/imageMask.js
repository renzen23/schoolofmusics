/* -------------------------------------------- */
/* IMAGE MASK ANIMATION */
/* -------------------------------------------- */

let $window = $(window);
let tmax_easeOut = Power4.easeOut;
let controller = new ScrollMagic.Controller();

// LOAD IMAGE MASK SVGS
// Loop through all img tags added in image_mask.blade.php with a data-src
// Insert inline svgs based on image data-src
// Must be data-src to prevent images from loading twice with Amazon
let $dataSvg = $('section.image-mask img[data-src]');
let countDataSvg = 0;
let totalDataSvg = $dataSvg.length;
$dataSvg.each(function () {
    let $img = $(this);
    let $parent = $img.parent();
    let imgID = $img.attr('id');
    let imgClass = $img.attr('class');
    let imgURL = $img.data('src');
    $.get(imgURL, function (data) {
        let $svg = $(data).find('svg');

        if (typeof imgID !== 'undefined') {
            $svg = $svg.attr('id', imgID);
        }
        if (typeof imgClass !== 'undefined') {
            $svg = $svg.attr('class', imgClass + ' replaced-svg');
        }
        $svg = $svg.removeAttr('xmlns:a');
        if (!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
            $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'));
        }
        // After inline SVGs are loaded, createImageMasks
        $img.replaceWith($svg).promise().done(function () {
            countDataSvg++;
            // Wait for all svgs to be loaded
            
            if (countDataSvg == totalDataSvg) {
                createImageMasks();
                loadFirstImageMask();
                animateImageMasks();
            }
        });
    }, 'xml');
});


// CREATE IMAGE MASKS
// Get all paths, rects from inline svg and place individual clipPaths
// Add in <image> tag for each clipPath
function createImageMasks() {
    $('section.image-mask').each(function () {
        let $section = $(this);

        $section.find('.image-mask').each(function () {
            let $imageMask = $(this);
            let $imageGroups = $imageMask.find('svg.image-groups');
            let $captions = $imageMask.find('svg.captions');
            let maskId = $imageMask.data('mask-id');
            let imageSrc = $imageMask.data('image-src');
            let activeType;
            let $activeClipPaths;

            // DESKTOP / MOBILE
            // ClipPath and image changes for desktop and mobile
            if ($section.hasClass('hero-mask')) {
                let responsive = ['mobile', 'desktop'];
                // Create clipPaths for both Mobile and Responsive
                responsive.forEach((elem) => {
                    // Get number of shapes inside the Image Mask
                    let $targetImg = $imageMask.find("img." + elem + "-svg");
                    let $shapes = $imageMask
                        .find('svg.replaced-svg.' + elem + '-svg')
                        .find('rect, path, polygon');
                    let $clipPaths = $imageMask.find('svg.clip-paths.' + elem + '-clip-paths defs');

                    // Create ClipPath for each shape
                    $shapes.each(function (i) {
                        let $shape = $(this);
                        let $clipPath = document.createElementNS('http://www.w3.org/2000/svg', 'clipPath');
                        $clipPath.id = maskId + '-index-' + i + '-' + elem;
                        $clipPath.appendChild($shape[0]);
                        $clipPaths.append($clipPath);
                    });
                });

                // Set ActiveClipPaths
                activeType = $(window).width() < 992 ? 'mobile' : 'desktop';
                $activeClipPaths = $imageMask
                    .find('svg.clip-paths.' + activeType + '-clip-paths defs')
                    .children();
            }

            //  LEFT / RIGHT
            // Image mask is flipped when the content is on the right or left side.
            if ($section.hasClass('custom-mask')) {
                let side = $section.hasClass('left') ? 'left' : 'right';
                let imageCaption = $imageMask.data('image-caption');
                let $svg = $imageMask.find('svg.replaced-svg.' + side + '-svg');
                let shapes = $svg.find('rect, path, polygon');
                let $clipPaths = $imageMask.find('svg.clip-paths.' + side + '-clip-paths defs');
                let text = $svg.find('text');
                $captions.append(text);
                $captions.find('text').text(imageCaption);

                // Create clipPath for each shape
                shapes.each(function (i) {
                    let $shape = shapes.eq(i);
                    let shapeId = maskId + '-index-' + i;
                    let $clipPath = document.createElementNS('http://www.w3.org/2000/svg', 'clipPath');
                    $clipPath.id = shapeId + '-' + side;
                    $clipPath.appendChild($shape[0]);
                    $clipPaths.append($clipPath);
                });

                // Set ActiveClipPaths
                activeType = side;
                $activeClipPaths = $imageMask
                    .find('svg.clip-paths.' + activeType + '-clip-paths defs')
                    .children();
            }

            // For each activeClipPaths create <image> tag and set size attributes.
            $activeClipPaths.each(function (i) {
                let imageId = $(this).attr('id');
                let $image = document.createElementNS('http://www.w3.org/2000/svg', 'image');
                $image.setAttribute('x', '0');
                $image.setAttribute('y', '0');
                if ($section.hasClass('hero-mask')) {
                    $image.setAttribute('width', '1550');
                    $image.setAttribute('height', '640');
                } else {
                    $image.setAttribute('width', '100%');
                    $image.setAttribute('height', '100%');
                    $image.setAttribute('preserveAspectRatio', 'xMinYMid slice');
                }
                // $image.setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', imageSrc);
                $image.setAttribute("data-lazy", imageSrc);
                $image = $($image);
                if (activeType == 'mobile' || activeType == 'desktop') {
                    let offset = $imageMask.data(activeType + '-offset');
                    $image.css('transform', 'translateX(' + offset / 10 + 'rem' + ')');
                }
                let $group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
                $group = $($group).css('clip-path', 'url(#' + imageId + ')');
                $group.append($image);
                $imageGroups.append($group);
            });

            // Remove image svgs for clarity
            // Initial <img> tags only used to gather data-src attribute
            $imageMask.find('.replaced-svg').remove();
        });
    });
}

/* -------------------------------------------- */
// LOAD FIRST IMAGE MASKS
function loadFirstImageMask() {
    $('section.image-mask').each(function () {
        let $section = $(this);
        let $imageMaskGroup = $(this).find('.image-mask-group');
        let $imageMask = $imageMaskGroup.find('.image-mask').eq(0);
        $imageMask.find(".image-groups g image").each(function () {
            $(this)[0].setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', $(this).data("lazy"));
        });
    });
}


/* -------------------------------------------- */
// LAZY LOAD
// Once page has loaded then load the rest of the image masks

$window.on("load", function () {

    $("section").each(function() {
        let $section = $(this);

        let s_loadIn = new ScrollMagic.Scene({
            triggerElement: $section[0],
            triggerHook: .6,
            reverse: false,
        })
        .on("enter", function() {
            $dataLazyImage = $section.find("image[data-lazy]");
            $dataLazyImage.each(function() {
                $(this)[0].setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', $(this).data("lazy"));
            });
        }).addTo(controller);
    });
});

/* -------------------------------------------- */
// LOAD ANIMATION
// Uses slick slider, custom animation added before and after change with TweenMax

function animateImageMasks() {
    $('section.image-mask').each(function () {
        let $section = $(this);
        let s_loadIn = new ScrollMagic.Scene({
            triggerElement: $section[0],
            triggerHook: 1,
            reverse: false,
        })
        .on('enter', function () {
            // SVGs on the Illustrator file are layered in reverse.
            let $reverseGroup = $($section.find('.image-mask').eq(0).find('g').get().reverse());
            let is_left = $section.hasClass("left");

            $reverseGroup.each(function (i) {
                // Animate in shapes with rhythm
                let k = i;
                if ((k + 1) % 3 == 0) {
                    k = k - 0.6;
                }

                TweenMax.fromTo(
                    $(this), 1, {
                        autoAlpha: 0,
                        x: is_left ? -15 : 15,
                    }, {
                        autoAlpha: 1,
                        x: 0,
                        ease: tmax_easeOut,
                        delay: k * 0.2 + 0.5,
                    }
                );
            });
        })
        .addTo(controller);
    });
}