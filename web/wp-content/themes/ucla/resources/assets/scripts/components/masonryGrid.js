/* -------------------------------------------- */
// Masonry grid
var $masonry = $('.masonry-container').masonry({
    itemSelector: '.masonry-item',
    percentPosition: true,
    columnWidth: '.masonry-sizer',
});

$(window).load(function () {
    // $masonry.imagesLoaded().progress( function() {
    $masonry.masonry();
    // });
});

var chunkCount = 0;
var lastChunk = $('.masonry-item').last().attr('data-chunk');
$('button.masonry-load-more').click(function () {
    chunkCount++;
    $('.chunk-' + chunkCount).removeClass('hidden');
    $masonry.masonry('layout');
    if (chunkCount == lastChunk) {
        $(this).css('display', 'none');
    }
});

document.addEventListener('lazybeforeunveil', function () {
    $('button.masonry-load-more').text('Loading...');
});

document.addEventListener('lazyloaded', function () {
    $('button.masonry-load-more').text('Load More');
});

var imageId = 0;
$('.masonry-item').click(function () {
    imageId = $(this).attr('data-image-id');
    var modal = $(this).closest('.b-image-masonry').next('#masonry-modal');
    modal.modal('show');
    modal.find('.masonry-modal-slider').slick({
        prevArrow: $('.masonry-prev'),
        nextArrow: $('.masonry-next'),
    });
    modal.find('.masonry-modal-slider').slick('slickGoTo', imageId);
});

$('#masonry-modal').on('hidden.bs.modal', function (e) {
    $('#masonry-modal .masonry-modal-slider').slick('unslick');
});

$('#masonry-modal .masonry-modal-slider').on('beforeChange', function(){
  let isVideo = $('#masonry-modal .masonry-modal-slider').find('.slick-current').children().hasClass('embed-container');
  if ( isVideo === true ) {
    $('#masonry-modal .masonry-modal-slider').find('.embed-container iframe').attr( 'src', function ( i, val ) { return val; });
  }
});
