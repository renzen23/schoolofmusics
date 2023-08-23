// Import external dependencies
import "jquery";
import "bootstrap/dist/js/bootstrap";
// Import Slick
import "slick-carousel/slick/slick.min";
// Import Masonry
import "masonry-layout/dist/masonry.pkgd.min.js";
// Import Imagesloaded
import "imagesloaded/imagesloaded.pkgd.min.js";
// Import Lazysizes
import "lazysizes/lazysizes.min.js";
// Import everything from autoload
import "./autoload/**/*";

// Font Awesome
import { library, dom } from "@fortawesome/fontawesome-svg-core";
// FA Brands
import faBrands from "@fortawesome/fontawesome-free-brands";

// add the imported icons to the library
library.add(faBrands);
dom.watch();

// Import local dependencies
import Router from "./util/Router";
import common from "./routes/common";
import home from "./routes/home";
import aboutUs from "./routes/about";
import hasEventsDirectory from "./routes/eventsDirectory";
import hasEnsemblesDirectory from "./routes/ensemblesDirectory";
import hasFacultyDirectory from "./routes/facultyDirectory";
import hasResearchDirectory from "./routes/researchDirectory";
import schoolOfMusicLiveStreams from "./routes/schoolOfMusicLiveStreams";

/** Populate Router instance with DOM routes */
const routes = new Router({
  // All pages
  common,
  // Home page
  home,
  // About Us page, note the change from about-us to aboutUs.
  aboutUs,
  hasEventsDirectory,
  hasEnsemblesDirectory,
  hasFacultyDirectory,
  hasResearchDirectory,
  schoolOfMusicLiveStreams
});

// Load Events
jQuery(document).ready(() => routes.loadEvents());
