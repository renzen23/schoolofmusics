<template>

	<div class="container">
		<div class="row">

			<div class="faculty-filter search-input cols">
                <faculty-search
					@triggerSearch="searchTerm"
				>
				</faculty-search>
			</div><!-- .faculty-filter -->

            <div class="faculty-filter dropdown-input cols">
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
					<li class="active">
						<a href="#" v-on:click.prevent="resetCategory()">All Departments</a>
					</li>
                    <faculty-category v-for="(item,index) in categories"
                        :item="item"
                        :loading="loading"
                        :key="index"
                        @chooseCategory="onChooseCategory"
                    >
                    </faculty-category>
                </ul>
            </div><!-- .faculty-filter -->
            <div class="faculty-filter dropdown-input cols">
                <button
                    type="button"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="true">
                    {{ selectedAreaValue }}
                    <img src="/app/themes/ucla/resources/assets/images/chevron.svg" width="16px" height="16px" class="arrow-down-icon svg" alt="dropdown">
                    <div class="sr-only">Dropdown</div>
                </button>
                <ul class="dropdown-menu">
					<li class="active">
						<a href="#" v-on:click.prevent="resetArea()">All Areas of Study</a>
					</li>
                    <faculty-category v-for="(item,index) in areas"
                        :item="item"
                        :key="index"
                        @chooseCategory="onChooseArea"
                    >
                    </faculty-category>
                </ul>
            </div><!-- .faculty-filter -->

		</div><!-- .row -->

		<div class="row">

			<div class="col-md-12">

				<transition-group name="fade" class="row grid-card-row" tag="div">
					<faculty-card v-for="(item,index) in items"
						:item="item"
						:key="index"
					>
					</faculty-card>
				</transition-group>

				<div class="loader" v-if="loading">Loading List...</div>

				<div id="faculty-list-bottom">
					<div v-if="noMoreItems">
						<!-- No more results. -->
					</div>
				</div>

			</div>

		</div><!-- .row -->
	</div>

</template>

<script>
import Vue from "vue";
import axios from "axios";
import scrollMonitor from "scrollmonitor";
import ScrollTo from 'jquery.scrollto'

import FacultyCategory from "./components/FacultyCategory.vue";
import FacultyCard from "./components/FacultyCard.vue";
import FacultySearch from "./components/FacultySearch.vue";

const load_num = 12;
const resultsPerQuery = 12;
const perPageText = 'per_page=' + resultsPerQuery;
const baseUrl = "/wp-json/wp/v2/people/?" + perPageText + "&people_category=36";
const peopleCategoriesListUrl = "/wp-json/wp/v2/department/?per_page=100";
const peopleAreasListUrl = "/wp-json/wp/v2/area_ensemble/?per_page=100";
const fields = "&fields=acf.title,title.rendered,link,acf.portrait.sizes.large,acf.last_name,department_name,area_ensemble_name";

const filterByCat = "/wp-json/wp/v2/people/?" + perPageText + "&department=";
const filterByArea = "/wp-json/wp/v2/people/?" + perPageText + "&area_ensemble=";

Vue.prototype.$http = axios;
export default {
	name: "FacultyDirectory",
	components: {
		FacultyCategory,
		FacultyCard,
		FacultySearch,
	},
	data() {
		return {
			categories: [],
			areas: [],
			categoryValue: "Sort By Program",
			areaValue: "Sort By Area of Study",
			chosenCategory: "",
			chosenArea: "",
			curPage: 1, // Pagination helper
			currentQuery: baseUrl, // This var let you concatenate queries
			items: [],
			loading: false,
			results: [],
			searchQuery: "",
			totalResults: 0,
			totalPages: 0,
		};
	},
	methods: {
		appendItems() {
			if( !this.loading ) {
				this.loading = true;
				if( this.items.length < this.totalResults ) {
					if ( this.curPage == 1 ) {
						let append = this.results.slice(this.items.length, this.items.length + load_num);
						this.items = this.items.concat(append);
						this.curPage++;
						this.loading = false;
					} else {
						let query = this.currentQuery + "&page=" + this.curPage;
						let append = [];
						axios
							.get(query)
							.then(response => {
								append = response.data;
								this.items = this.items.concat(append);
								this.loading = false;
								if( this.curPage < this.totalPages ) {
									this.curPage++;
								}
							})
					}
				} else {
					this.loading = false;
				}
			}
		},
		getResults(customUrl = baseUrl) {
			this.curPage = 1;
			this.loading = true;
			let self = this;
			this.items = [];
			this.results = [];
			this.currentQuery = customUrl.replace(this.searchQuery, "");
			// Show only required fields
			let wpAPIUrl = customUrl + fields;
			axios
				.get(wpAPIUrl)
				.then(response => {
					this.results = response.data;
					this.totalResults = parseInt(response.headers["x-wp-total"]);
					this.totalPages = parseInt(response.headers["x-wp-totalpages"]);
					this.loading = false;
					this.appendItems();
				})
				.catch(response => {
					console.log(response);
				});
		},
        getCategories() {
			let self = this;
			axios
				.get(peopleCategoriesListUrl)
				.then(response => {
					let matchCat = '';
					this.categories = response.data;
					let matches = location.search.match(/department=([^&]*)/i);
					if( matches ) {
						matchCat = this.categories.find(obj => {
							return obj.slug === matches[1];
						});
					}
					if (matchCat) {
						self.readQs(matchCat);
					} else {
						self.getResults();
					}
				})
				.catch(response => {
					console.log(response);
				});
		},
        getAreas() {
			let self = this;
			axios
				.get(peopleAreasListUrl)
				.then(response => {
					let matchCat = '';
					this.areas = response.data;
					let matches = location.search.match(/area_ensemble=([^&]*)/i);
					if( matches ) {
						matchCat = this.areas.find(obj => {
							return obj.slug === matches[1];
						});
					}
					if (matchCat) {
						self.readAreaQs(matchCat);
					} else {
						self.getResults();
					}
				})
				.catch(response => {
					console.log(response);
				});
		},
		searchTerm(term) {
			let searchQuery = '';
			if( term !== "" ) {
				this.searchQuery = '&search=' + term;
			} else {
				this.searchQuery = '';
			}
			searchQuery = this.currentQuery + this.searchQuery;
			this.getResults(searchQuery);
		},
		onChooseCategory(item) {
			this.areaValue = null;
			this.curPage = 1;
            this.categoryValue = item.name;
			this.chosenCategory = item.id;
			this.currentUrl = filterByCat + item.id + this.searchQuery;
			this.getResults(this.currentUrl);
		},
		onChooseArea(item) {
			this.categoryValue = null;
			this.curPage = 1;
            this.areaValue = item.name;
			this.chosenArea = item.id;
			this.currentUrl = filterByArea + item.id + this.searchQuery;
			this.getResults(this.currentUrl);
		},
		resetCategory(){
			this.categoryValue = null;
			this.getResults();
		},
		resetArea(){
			this.areaValue = null;
			this.getResults();
		},
		readQs(match) {
			this.onChooseCategory(match);
			window.preventNavDown = true
			$(window).scrollTo($(".b-faculty-directory"), 200, {
				onAfter: function() {
					window.setTimeout(function() {
					window.preventNavDown = false
					}, 300)
				},
			})
		},
		readAreaQs(match) {
			this.onChooseArea(match);
			window.preventNavDown = true
			$(window).scrollTo($(".b-faculty-directory"), 200, {
				onAfter: function() {
					window.setTimeout(function() {
					window.preventNavDown = false
					}, 300)
				},
			})
		},
	},
	computed: {
		noMoreItems: function() {
			return this.items.length === this.totalResults && this.results.length > 0;
        },
        selectedValue: function() {
            return this.categoryValue || 'All Departments';
        },
        selectedAreaValue: function() {
            return this.areaValue || 'All Areas of Study';
        }
	},
	mounted() {
		let self = this;
		this.getCategories();
		this.getAreas();
		var elem = document.getElementById('faculty-list-bottom');
		var watcher = scrollMonitor.create(elem);
		watcher.enterViewport(function() {
			self.appendItems();
		});
	},
};
</script>
