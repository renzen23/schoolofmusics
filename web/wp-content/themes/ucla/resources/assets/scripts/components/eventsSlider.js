export default function eventsSlider() {
  const MD = 768;
  const LG = 992;

  const baseSlickOptions = {
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 1
  };

  $(".events-slider:not(.featured-events-slider)").slick({
    ...{
      ...baseSlickOptions,
      prevArrow: $(".events-slider:not(.featured-events-slider)").siblings(
        ".slick-prev-arrow"
      ),
      nextArrow: $(".events-slider:not(.featured-events-slider)").siblings(
        ".slick-next-arrow"
      )
    },
    ...{
      responsive: [
        {
          breakpoint: LG,
          settings: {
            slidesToShow: 2
          }
        },
        {
          breakpoint: MD,
          settings: {
            slidesToShow: 1
          }
        }
      ]
    }
  });

  $(".events-slider.featured-events-slider").slick({
    ...{
      ...baseSlickOptions,
      prevArrow: $(".events-slider.featured-events-slider").siblings(
        ".slick-prev-arrow"
      ),
      nextArrow: $(".events-slider.featured-events-slider").siblings(
        ".slick-next-arrow"
      )
    },
    ...{
      responsive: [
        {
          breakpoint: LG,
          settings: {
            centerMode: true,
            slidesToShow: 1
          }
        },
        {
          breakpoint: MD,
          settings: {
            arrows: false,
            centerMode: true,
            centerPadding: "20px",
            slidesToShow: 1
          }
        }
      ]
    }
  });
}
