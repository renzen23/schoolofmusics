<template>
	<div>

        <div class="row">
            <div class="research-filter search-input cols">
                 <research-search
					@triggerSearch="searchTerm"
				>
				</research-search>
            </div><!-- .research-filter -->


            <div class="research-filter research-year dropdown-input cols">
                <button
                    type="button"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="true">
                    {{ selectedYear }}
                    <img src="/app/themes/ucla/resources/assets/images/chevron.svg" width="16px" height="16px" class="arrow-down-icon svg" alt="dropdown">
                    <div class="sr-only">Dropdown</div>
                </button>
                <ul class="dropdown-menu">
					<li>
						<a href="#" v-on:click.prevent="resetYear()">All</a>
					</li>
                    <research-year v-for="(item,index) in years"
                        :item="item"
                        :key="index"
                        @chooseYear="onChooseYear"
                    >
                    <!-- {{ $index }} -->
                    </research-year>
                </ul>
            </div><!-- .research-filter -->

            <div class="research-filter dropdown-input cols">
                <button
                    type="button"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="true">
                    {{ selectedValue }}
                    <img src="/app/themes/ucla/resources/assets/images/chevron.svg" width="16px" height="16px" class="arrow-down-icon svg" alt="dropdown">
                    <div class="sr-only">Dropdown</div>
                </button>
                <ul class="dropdown-menu">
					<li>
						<a href="#" v-on:click.prevent="resetCategory()">All</a>
					</li>
                    <research-category v-for="(item,index) in categories"
                        :item="item"
                        :key="index"
                        @chooseCategory="onChooseCategory"
                    >
                    <!-- {{ $index }} -->
                    </research-category>
                </ul>
            </div><!-- .research-filter -->
        </div>

        <div class="row loader" v-if="loading">
			<div class="col-12 text-center">
				Loading List...
			</div>
		</div>

		<div class="row accordion-group transition-group">
            <transition-group name="fade" tag="div">
                <research-accordion v-for="(item,index) in items"
                    :item="item"
                    :key="index"
                >
                </research-accordion>
            </transition-group>
		</div><!-- .row -->

		<div class="row loader" v-if="loadingExtra">
			<div class="col-12 text-center">
				Loading more results...
			</div>
		</div>

		<div class="row" v-if="results.length == 0 && !loading">
			<div class="col-12">
				<div class="text-center">
					No documents available.
				</div>
			</div>
		</div>

        <div class="row" v-if="!noMoreItems && !loading &&!loadingExtra">
            <div class="col-12 mt-3 text-center">
				<a href="#" v-on:click.prevent="loadMore" class="btn btn-primary">Load More</a>
            </div>
        </div>

	</div>
</template>

<script>
import Vue from "vue";
import axios from "axios";
import scrollMonitor from "scrollmonitor";

import ResearchCategory from "./components/ResearchCategory.vue";
import ResearchYear from "./components/ResearchYear.vue";
import ResearchAccordion from "./components/ResearchAccordion.vue";
import ResearchSearch from "./components/ResearchSearch.vue";

const load_num = 12;
const baseUrl = "/wp-json/wp/v2/publications/?per_page=100";
// 25, 26
const researchDepartPrograms = "/wp-json/wp/v2/categories/?per_page=100&include=11,23";
// const fields = "&fields=title.rendered,link,acf.title,acf.year,acf.program.title,acf.description";
const filterByCat = "/wp-json/wp/v2/publications?per_page=100&categories=";
const filterByYear = "/wp-json/wp/v2/publications?per_page=100&fields=title.rendered,content,acf&filter[meta_key]=year&filter[meta_value]=";

Vue.prototype.$http = axios;

export default {
	name: "ResearchDirectory",
	components: {
		ResearchCategory,
		ResearchAccordion,
		ResearchYear,
		ResearchSearch,
	},
	data() {
		return {
			appendCount: 0,
			currentUrl: baseUrl,
			chosenCategory: "Categories",
			items: [],
			loading: false,
			loadingExtra: false,
			offSet: 1,
			results: [],
			years: [],
			chosenYear: "Year",
            categories: [],
			categoryValue: "All",
			categoryIsActive: false,
			yearIsActive: false,
			searchTermString: '',
		};
	},
	methods: {
		appendItems() {
			if (this.items.length < this.results.length) {
				this.appendCount++;
				if( (this.appendCount + 1) * load_num > ( 100 * this.offSet ) ) {
					this.extendResults();
				} else {
					let append = this.results.slice(this.items.length, this.items.length + load_num);
					this.items = this.items.concat(append);
				}
			}
		},
		getResults(customUrl = baseUrl) {
			this.appendCount = 0;
			this.offSet = 1;
			this.loading = true;
			let self = this;
			this.items = [];
            this.results = [];
			this.currentUrl = customUrl;
			// Include Search if string exists
			if( this.searchTermString ) {
				customUrl += this.searchTermString;
			}
            let wpAPIUrl = customUrl;
			axios
				.get(wpAPIUrl)
				.then(response => {
					this.results = response.data;
					this.appendItems();
					this.loading = false;
				})
				.catch(response => {
					console.log(response);
				});
		},
		extendResults() {
			this.loadingExtra = true;
			// console.log(this.currentUrl + '&offset=2')
			axios
				.get(this.currentUrl + '&offset=' + this.offSet * 100)
				.then(response => {
					let extraResults = response.data;
					this.offSet++;
					this.results = this.results.concat(extraResults);
					this.appendItems();
					this.loadingExtra = false;
				})
				.catch(response => {
					console.log(response);
				});
		},
        searchTerm(term) {
			this.searchTermString = '&search=' + term;
			this.getResults();
        },
        getCategories() {
			axios
				.get(researchDepartPrograms)
				.then(response => {
					this.categories = response.data;
					//console.log(this.categories);
				})
				.catch(response => {
					console.log(response);
				});
		},
        getYears() {

			var currentYear = new Date().getFullYear(), years = [];
			var startYear = 1990;

			while ( startYear <= currentYear ) {
				years.push(startYear++);
			}
			this.years = years.reverse();

			//console.log(this.years);

		},
		
		// Hit when choosing any category
		onChooseCategory(item) {
            this.categoryValue = item.name;
			this.chosenCategory = item.id;
			
			this.currentUrl = filterByCat + item.id;
			this.categoryIsActive = true;

			if( this.yearIsActive ) {
				this.currentUrl += '&fields=title.rendered,content,acf&filter[meta_key]=year&filter[meta_value]=' + this.selectedYear;
			}
			this.getResults(this.currentUrl );
		},

		// Hit when choosing any year
		onChooseYear(item) {
			//console.log(item);
            this.chosenYear = item;
            this.currentUrl = filterByYear + item;
			this.getResults(this.currentUrl );

			this.yearIsActive = true;

			if( this.categoryIsActive ) {
				this.currentUrl += '&categories=' + this.chosenCategory;
			}
		},

		// Hit if All Category is selected
		resetCategory(){
			this.currentUrl = baseUrl;
			this.categoryIsActive = false;

			if( this.yearIsActive ) {
				this.currentUrl += '&fields=title.rendered,content,acf&filter[meta_key]=year&filter[meta_value]=' + this.selectedYear;
			}

			this.categoryValue = null;
			this.getResults(this.currentUrl);
		},

		// Hit if All years value is selected
		resetYear(){
			this.currentUrl = baseUrl;
			this.yearIsActive = false;

			if( this.categoryIsActive ) {
				this.currentUrl += '&categories=' + this.chosenCategory;
			} 

			this.chosenYear = "All";
			this.getResults(this.currentUrl);
		},
		// Load More Items
		loadMore() {
			this.appendItems();
		},
	},
	computed: {
		noMoreItems: function() {
			return (this.items.length === this.results.length && this.results.length > 0) || this.results.length === 0;
        },
        selectedValue: function() {
            return this.categoryValue || 'All';
        },
        selectedYear: function() {
            return this.chosenYear;
		},
	},
	mounted() {
		this.getResults();
        this.getCategories();
        this.getYears();
        // console.log(this.categories);
		let self = this;
		var elem = document.getElementById('research-list-bottom');
		// var watcher = scrollMonitor.create(elem);
		// watcher.enterViewport(function() {
		// 	self.appendItems();
		// });
	},
};
</script>
