<template>
  <div class="container">
    <div class="events-sidebar row">
      <div class="event-filter col-md-6 col-lg-3">
        <date-picker
          class="form-input event-filter-dropdown"
          v-model="date"
          format="MMMM D, YYYY"
          :options="{ firstDay: 1 }"
          v-on:input="selectedDate($event)"
        ></date-picker>
      </div>

      <div
        class="event-filter filter-btn d-flex justify-content-end d-lg-none col-md-6"
        v-on:click="showFilters = !showFilters"
        v-bind:class="{ open: showFilters }"
      >
        <span>
          Filter
          <img
            src="/app/themes/ucla/resources/assets/images/filter.svg"
            width="16px"
            height="16px"
            class="filter-icon svg"
            alt="filter"
          />
          <img
            src="/app/themes/ucla/resources/assets/images/filter-selected.svg"
            width="16px"
            height="16px"
            class="filter-icon-selected svg"
            alt="selected filter"
          />
          <div class="sr-only">Filter</div>
        </span>
      </div>

      <div
        class="event-filter-dropdown mobile-filters-container offset-md-6 col-md-6 offset-lg-0 col-lg-9 d-lg-flex"
        v-bind:class="{ open: showFilters }"
      >
        <div class="faculty-filter dropdown-input col-12 col-lg-4">
          <h3>Event Categories</h3>
          <button
            type="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="true"
            class="event-filter-dropdown"
          >
            {{ activeCategory ? activeCategory.name : "All Categories" }}
            <chevron-icon />
            <div class="sr-only">Dropdown</div>
          </button>
          <ul class="dropdown-menu">
            <li class="active">
              <a href="#" v-on:click.prevent="() => reset('categories')"
                >All Categories</a
              >
            </li>
            <event-category
              v-for="(item, index) in categories.filter(
                cat => cat.slug !== 'student-recital'
              )"
              :item="item"
              :key="index"
              :index="index"
              @chooseCategory="onChooseCategory"
              route="cat"
            >
            </event-category>
          </ul>
        </div>

        <div class="faculty-filter dropdown-input col-12 col-lg-4">
          <h3>Special Programs</h3>
          <button
            type="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="true"
            class="event-filter-dropdown"
          >
            {{ activeSerie ? activeSerie.name : "All Special Programs" }}
            <chevron-icon />
            <div class="sr-only">Dropdown</div>
          </button>
          <ul class="dropdown-menu">
            <li class="active">
              <a href="#" v-on:click.prevent="() => reset('series')"
                >All Special Programs</a
              >
            </li>
            <event-category
              v-for="(item, index) in series"
              :item="item"
              :key="index"
              :index="index"
              @chooseCategory="onChooseSerie"
              route="special-program"
            >
            </event-category>
          </ul>
        </div>

        <div
          class="event-filter calendar-subscription col-12 col-lg-4 d-flex justify-content-center align-items-start"
          v-if="eventsSubscriptionLink"
        >
          <a :href="eventsSubscriptionLink" class="cta pill-cta yellow">
            <span>
              Subscribe to Calendar
            </span>
          </a>
        </div>
      </div>
    </div>

    <div class="events-content">
      <transition-group name="fade" tag="div" class="events-list row">
        <event-card v-for="event in events" :event="event" :key="event.id" />
      </transition-group>

      <div class="loader" v-if="loading">Loading Events...</div>

      <div class="pagination">
        <button
          class="prev-arrow"
          v-if="curPage > 1"
          @click="
            () => {
              this.curPage = this.curPage - 1;
              appendItems();
            }
          "
        >
          <span class="icon">
            <arrow-icon />
          </span>
          Previous Events
        </button>
        <button
          class="next-arrow"
          v-if="!noMoreItems"
          @click="
            () => {
              this.curPage = this.curPage + 1;
              appendItems();
            }
          "
        >
          Next Events
          <span class="icon">
            <arrow-icon />
          </span>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import axios from "axios";
import moment from "moment/src/moment";

import DatePicker from "./components/DatePicker.vue";
import EventCategory from "./components/EventCategory.vue";
import EventCard from "./components/EventCard.vue";
import ChevronIcon from "./components/ChevronIcon.vue";
import ArrowIcon from "./components/ArrowIcon.vue";

const load_num = 12;
const resultsPerQuery = 12;
const perPageText = "per_page=" + resultsPerQuery;
const baseUrl =
  "/wp-json/ucla/events/v1/events/?exclude_recitals=1&" + perPageText;
const eventsCategoriesListUrl = "/wp-json/events/categories/";
// const eventsCategoriesListUrl = "/wp-json/tribe/events/v1/categories/";
const eventsSeries = "/wp-json/events/series/";
// const eventsSeries = "/wp-json/wp/v2/event_series/";
const filterByCat =
  "/wp-json/ucla/events/v1/events/?" + perPageText + "&categories[]=";
const filterBySerie =
  "/wp-json/ucla/events/v1/events?" + perPageText + "&event_series=";

Vue.prototype.$http = axios;

export default {
  name: "EventsDirectory",
  components: {
    DatePicker,
    EventCategory,
    EventCard,
    ChevronIcon,
    ArrowIcon
  },
  props: {
    eventsSubscriptionLink: {
      required: false,
      default: ""
    }
  },
  data() {
    return {
      activeCategory: null, // Decides whether category filter has an active class or not
      activeSerie: null,
      categories: [], // Categories array to display on the sidebar
      curPage: 1, // Pagination helper
      currentQuery: baseUrl, // This var let you concatenate queries
      date: moment().format("MMMM D, YYYY"), // Today value in a custom format for date input.
      dateParams: "",
      fullList: true,
      events: [], // Items which are going to be displayed
      loading: false, // Initial load State
      results: [],
      series: [], //
      startingDate: moment().format("YYYY-MM-DD"),
      showFilters: false,
      totalResults: 0,
      totalPages: 0,
      livestreamUrl: ""
    };
  },
  methods: {
    appendItems() {
      if (this.curPage > this.totalPages) return;

      if (!this.loading) {
        this.loading = true;
        if (this.events.length < this.totalResults) {
          if (this.curPage == 1) {
            this.events = this.results.slice(0, load_num);
            this.loading = false;
          } else {
            let query = this.currentQuery + "&page=" + this.curPage;
            if (query.indexOf("start_date") < 0) {
              let today = moment(new Date()).format("YYYY-MM-DD");
              // console.log(today);
              query = query + "&start_date=" + today;
            }
            let append = [];
            axios.get(query).then(response => {
              append = response.data.events;
              if (this.events[this.events.length - 1].url == append[0].url) {
                this.events.splice(-1, 1);
              }
              this.events = append;
              this.loading = false;
            });
          }
        } else {
          this.loading = false;
        }
        // if (this.events.length < this.results.length) {
        // 	let append = this.results.slice(this.events.length, this.events.length + load_num);
        // 	this.events = this.events.concat(append);
        // }
      }
    },
    getResults(customUrl = baseUrl) {
      this.curPage = 1;
      this.events = [];
      this.results = [];
      this.loading = true;
      this.currentQuery = customUrl;
      if (customUrl.indexOf("start_date") < 0) {
        let today = moment(new Date()).format("YYYY-MM-DD");
        // console.log(today);
        customUrl = customUrl + "&start_date=" + today;
      }
      axios
        .get(customUrl)
        .then(response => {
          this.results = response.data.events;
          this.totalResults = parseInt(response.headers["x-tec-total"]);
          this.totalPages = parseInt(response.headers["x-tec-totalpages"]);
          this.loading = false;

          this.appendItems();
        })
        .catch(response => {
          console.log(response);
        });
    },
    getCategories() {
      axios
        .get(eventsCategoriesListUrl)
        .then(response => {
          this.categories = response.data;

          // Load Series
          this.getSeries();
        })
        .catch(response => {
          console.log(response);
        });
    },
    getSeries() {
      axios
        .get(eventsSeries)
        .then(response => {
          this.series = response.data;

          // Load events based on whether they have params on the route or not
          this.initialLoad();
        })
        .catch(response => {
          console.log(response);
        });
    },
    initialLoad() {
      // If initial route contains a program property show results
      if (this.$route.params.hasOwnProperty("program")) {
        this.checkSerie();
        // If initial route contains a cat property show results
      } else if (this.$route.params.hasOwnProperty("category")) {
        this.checkCat();
      } else {
        // Show all results
        this.onChooseAll();
      }
    },
    searchPosts(terms) {
      this.setNumberofPages();
    },
    onChooseCategory(item, index) {
      this.activeSerie = null;
      this.fullList = false;
      this.curPage = 1;

      let newUrl = "";

      this.activeCategory = item;
      newUrl = filterByCat + item.term_id + this.dateParams;

      this.getResults(newUrl);
    },
    onChooseAll() {
      this.fullList = true;

      this.activeCategory = null;
      this.activeSerie = null;

      this.getResults(baseUrl + this.dateParams);
    },
    onChooseSerie(item, index) {
      this.activeCategory = null;
      this.fullList = false;
      this.curPage = 1;
      let newUrl = this.baseUrl;

      this.activeSerie = item;
      newUrl = filterBySerie + item.slug + this.dateParams;

      this.getResults(newUrl);
    },

    selectedDate(e) {
      let formattedValue = moment(e.date, "MMMM D, YYYY").format("YYYY-MM-DD");
      let appendEndDate = "";

      if (e.type == "today" || e.type == "tomorrow") {
        appendEndDate = "&end_date=" + formattedValue;
      }

      if (e.type == "weekend") {
        appendEndDate =
          "&end_date=" +
          moment(e.date, "MMMM D, YYYY")
            .add(1, "days")
            .format("YYYY-MM-DD");
      }

      // If you only want to display the exact date uncomment this line and comment the next
      // this.dateParams = "start_date=" + formattedValue + appendEndDate;

      // Only if you want to show all events starting from today, tomorrow, weekend
      this.dateParams = "&start_date=" + formattedValue;

      this.startingDate = formattedValue;
      let newUrl = baseUrl + this.dateParams;
      this.getResults(newUrl);
    },

    checkCat() {
      let chosenSlug = this.$route.params.category;
      let result = this.categories.filter(function(entry) {
        return entry.slug === chosenSlug;
      });
      if (result.length > 0) {
        let firstMatch = result.shift();
        let catIndex = this.categories.indexOf(firstMatch);
        this.onChooseCategory(firstMatch, catIndex);
      } else {
        this.onChooseAll();
      }
    },

    checkSerie() {
      let chosenSlug = this.$route.params.program;
      let result = this.series.filter(function(entry) {
        return entry.slug === chosenSlug;
      });
      if (result.length > 0) {
        let firstMatch = result.shift();
        let serieIndex = this.series.indexOf(firstMatch);
        this.onChooseSerie(firstMatch, serieIndex);
      } else {
        this.onChooseAll();
      }
    },

    reset(collection) {
      this.fullList = true;
      this.curPage = 1;
      if (collection === "series") this.activeSerie = null;
      if (collection === "categories") this.activeCategory = null;
      this.$router.push("/");
      this.getResults(baseUrl + this.dateParams);
    }
  },
  watch: {
    // '$route': 'checkCat',
    $route(to) {
      var param = Object.keys(to.params)[0];
      if (param === "category") {
        this.checkCat();
      } else if (param === "program") {
        this.checkSerie();
      } else {
        this.onChooseAll();
      }
    }
  },
  computed: {
    noMoreItems: function() {
      return this.curPage + 1 > this.totalPages;
    },
    noEvents: function() {
      return !this.loading && this.totalResults === 0;
    },
    studentCategory: function() {
      return this.categories.find(x => x.slug === "student-recital");
    },
    studentCategoryIndex: function() {
      let index = this.categories.findIndex(function(x) {
        if (x.hasOwnProperty("slug")) {
          return x.slug === "student-recital";
        } else {
          return false;
        }
      });
      return index;
    }
  },
  mounted() {
    let parentEl = jQuery("#listing");

    this.getCategories();

    this.livestreamUrl = parentEl.data("livestream");
  }
};
</script>
