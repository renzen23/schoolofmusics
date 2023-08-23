// Scrolling animations, e.g. fade in text
import "../components/scrollingAnimations";
// Create image masks and animate in
import "../components/imageMask";
// Create image masks and slider components
import "../components/navigationCarousel";
// Slider component for hero module
import "../components/heroSlider";
// Slider component for qutoes module
import "../components/quotesSlider";
// Slider component for people grid
import "../components/peopleSlider";
// Masonry Grid module
import "../components/masonryGrid";
// Header navigation
import "../components/navigation";

import eventsSlider from "../components/eventsSlider";

export default {
  init() {
    let $window = $(window);

    /* -------------------------------------------- */
    $window.load(function() {
      // Prevent unintended transitions on page load https://css-tricks.com/transitions-only-after-page-load/
      $("body").removeClass("preload");
    });

    eventsSlider();

    /* -------------------------------------------- */
    // SOCIAL MEDIA LINKS on event page
    function setShareLinks() {
      var pageUrl = encodeURIComponent(document.URL);

      $(".social-share.facebook").on("click", function() {
        let url = "https://www.facebook.com/sharer.php?u=" + pageUrl;
        window.open(url, "_blank");
      });

      $(".social-share.twitter").on("click", function() {
        let tweet = $(this).data("text");
        let url =
          "https://twitter.com/intent/tweet?url=" + pageUrl + "&text=" + tweet;
        window.open(url, "_blank");
      });

      $(".social-share.linkedin").on("click", function() {
        let url =
          "https://www.linkedin.com/shareArticle?mini=true&url=" + pageUrl;
        window.open(url, "_blank");
      });
    }
    setShareLinks();

    /* -------------------------------------------- */
    // VIDEO MODULE
    // Show video on click and remove gradients
    $("section.b-video .video-trigger").click(function(e) {
      let $this = $(this);
      let $parent = $this.parent();
      e.preventDefault();
      $parent.addClass("show-video");
      if ($parent.find(".embed-container").hasClass("img-preview")) {
        let iframe = $parent.find(".embed-container").data("iframe");
        $parent.find(".embed-container").append(iframe);
        $parent.find(".embed-container iframe")[0].src += "&autoplay=1";
      } else {
        $parent.find("iframe")[0].src += "&autoplay=1";
      }
    });

    /* -------------------------------------------- */
    // BLOCK SECTION STYLING
    // Add border top to section container not followed by section with background color
    // Should also work in situations with background image
    function elemHasBgColor($elem) {
      return (
        $elem.hasClass("yellow-bg") ||
        $elem.hasClass("blue-bg") ||
        $elem.hasClass("light-bg") ||
        $elem.hasClass("black-bg") ||
        $elem.hasClass("background-image")
      );
    }
    let $mainSection = $("main.main section");
    $mainSection.each(function(index) {
      if (index != 0) {
        let $this = $(this);
        let $prev = $this.prev();
        let this_section_has_bg = elemHasBgColor($this);
        let prev_section_has_bg = elemHasBgColor($prev);
        if (
          !prev_section_has_bg &&
          !this_section_has_bg &&
          !$(this).hasClass("b-contact-form")
        ) {
          $this.addClass("border-top");
        }
      }
    });

    /* -------------------------------------------- */
    // REPLACE SVG images with inline SVG
    imgToSvg();
    $window.load(function() {
      // Load again for vue modules
      imgToSvg();
    });

    function imgToSvg() {
      $("img.svg").each(function() {
        var $img = $(this);
        var imgID = $img.attr("id");
        var imgClass = $img.attr("class");
        var imgURL = $img.attr("src");
        $.get(
          imgURL,
          function(data) {
            // Get the SVG tag, ignore the rest
            var $svg = $(data).find("svg");
            // Add replaced image's ID to the new SVG
            if (typeof imgID !== "undefined") {
              $svg = $svg.attr("id", imgID);
            }
            // Add replaced image's classes to the new SVG
            if (typeof imgClass !== "undefined") {
              $svg = $svg.attr("class", imgClass + " replaced-svg");
            }
            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr("xmlns:a");
            // Check if the viewport is set, if the viewport is not set the SVG wont't scale.
            if (
              !$svg.attr("viewBox") &&
              $svg.attr("height") &&
              $svg.attr("width")
            ) {
              $svg.attr(
                "viewBox",
                "0 0 " + $svg.attr("height") + " " + $svg.attr("width")
              );
            }
            // Replace image with new SVG
            $img.replaceWith($svg);
          },
          "xml"
        );
      });
    }

    /* -------------------------------------------- */
    // PHOTO CREDIT Open photo credit popup in footer
    $("body").click(function() {
      $(".photo-credits").removeClass("shown");
    });

    $(".photo-credit-button").click(function(e) {
      e.stopPropagation();
      $(".photo-credits").toggleClass("shown");
    });

    /* -------------------------------------------- */
    // CALENDAR PAGE - fix sidebar on desktop
    $(window).load(function() {
      if ($(".b-events-directory").find(".events-sidebar").length) {
        function fixedOrNot() {
          let windowHeight = $(window).height();
          let sidebarHeight = $(".b-events-directory")
            .find(".events-sidebar")
            .height();
          let scrollPosition = $(window).scrollTop();

          if (windowHeight >= sidebarHeight + 350 && scrollPosition >= 80) {
            $(".events-sidebar").addClass("fixed");
          } else {
            $(".events-sidebar").removeClass("fixed");
          }
        }
        fixedOrNot();

        var fixedTimer;
        $(window).scroll(function() {
          fixedOrNot();
        });
        $(window).resize(function() {
          clearTimeout(fixedTimer);
          fixedTimer = setTimeout(function() {
            fixedOrNot();
          }, 250);
        });
      }
    });

    /* -------------------------------------------- */
    // Include ie11 polyfill
    let isIE11 = !!window.MSInputMethodContext && !!document.documentMode;
    if (isIE11) {
      $("body").addClass("ie11");
    }
    if (!Array.prototype.find) {
      Array.prototype.find = function(predicate) {
        if (this == null) {
          throw new TypeError(
            "Array.prototype.find called on null or undefined"
          );
        }
        if (typeof predicate !== "function") {
          throw new TypeError("predicate must be a function");
        }
        var list = Object(this);
        var length = list.length >>> 0;
        var thisArg = arguments[1];
        var value;

        for (var i = 0; i < length; i++) {
          value = list[i];
          if (predicate.call(thisArg, value, i, list)) {
            return value;
          }
        }
        return undefined;
      };
    }

    if (!Array.prototype.findIndex) {
      Object.defineProperty(Array.prototype, "findIndex", {
        value: function(predicate) {
          // 1. Let O be ? ToObject(this value).
          if (this == null) {
            throw new TypeError('"this" is null or not defined');
          }

          var o = Object(this);

          // 2. Let len be ? ToLength(? Get(O, "length")).
          var len = o.length >>> 0;

          // 3. If IsCallable(predicate) is false, throw a TypeError exception.
          if (typeof predicate !== "function") {
            throw new TypeError("predicate must be a function");
          }

          // 4. If thisArg was supplied, let T be thisArg; else let T be undefined.
          var thisArg = arguments[1];

          // 5. Let k be 0.
          var k = 0;

          // 6. Repeat, while k < len
          while (k < len) {
            // a. Let Pk be ! ToString(k).
            // b. Let kValue be ? Get(O, Pk).
            // c. Let testResult be ToBoolean(? Call(predicate, T, « kValue, k, O »)).
            // d. If testResult is true, return k.
            var kValue = o[k];
            if (predicate.call(thisArg, kValue, k, o)) {
              return k;
            }
            // e. Increase k by 1.
            k++;
          }

          // 7. Return -1.
          return -1;
        },
        configurable: true,
        writable: true
      });
    }

    if (!Object.assign) {
      Object.defineProperty(Object, "assign", {
        enumerable: false,
        configurable: true,
        writable: true,
        value: function(target) {
          "use strict";
          if (target === undefined || target === null) {
            throw new TypeError("Cannot convert first argument to object");
          }

          var to = Object(target);
          for (var i = 1; i < arguments.length; i++) {
            var nextSource = arguments[i];
            if (nextSource === undefined || nextSource === null) {
              continue;
            }
            nextSource = Object(nextSource);

            var keysArray = Object.keys(Object(nextSource));
            for (
              var nextIndex = 0, len = keysArray.length;
              nextIndex < len;
              nextIndex++
            ) {
              var nextKey = keysArray[nextIndex];
              var desc = Object.getOwnPropertyDescriptor(nextSource, nextKey);
              if (desc !== undefined && desc.enumerable) {
                to[nextKey] = nextSource[nextKey];
              }
            }
          }
          return to;
        }
      });
    }

    // Auto more
    if ($(".auto-more").length) {
      $(".auto-more").each(function() {
        $(this)
          .find("p")
          .not(":first")
          .hide();
        $(this).append(
          "<button class='toggle-auto-more btn btn-secondary'>Read More</button>"
        );
      });
      $(".toggle-auto-more").click(function() {
        $(this).hide();
        $(this)
          .parents(".auto-more")
          .find("p")
          .show();
      });
    }
  },
  finalize() {
    setTimeout(function() {
      if (location.hash) {
        console.log(location.hash);
        let elem = document.getElementById(location.hash.substring(1));
        if (elem) {
          elem.scrollIntoView();
        }
      }
    }, 1000);
  }
};
