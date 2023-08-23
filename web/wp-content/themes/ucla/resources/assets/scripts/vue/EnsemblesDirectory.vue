<template>

	<div class="container">
		<div class="row">
            <div class="ensemble-filter search-input cols">
                 <ensemble-search
					@triggerSearch="searchTerm"
				>
				</ensemble-search>
            </div><!-- .ensemble-filter -->

            <div class="ensemble-filter dropdown-input cols">
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
						<a href="#" v-on:click.prevent="resetCategory()">All</a>
					</li>
                    <ensemble-category v-for="(item,index) in categories"
                        :item="item"
                        :key="index"
                        @chooseCategory="onChooseCategory"
                    >
                    </ensemble-category>
                </ul>
            </div><!-- .ensemble-filter -->

		</div><!-- .row -->

		<div class="row">

			<div class="col-md-12">

				<transition-group name="fade" class="row grid-card-row" tag="div">
					<ensemble-card v-for="(item,index) in items"
						:item="item"
						:key="index"
					>
					</ensemble-card>
				</transition-group>

				<div class="loader" v-if="loading">Loading Ensembles...</div>

				<div id="ensemble-list-bottom">
					<div v-if="noMoreItems">
						<!-- No more Ensembles. -->
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

import EnsembleCategory from "./components/EnsembleCategory.vue";
import EnsembleCard from "./components/EnsembleCard.vue";
import EnsembleSearch from "./components/EnsembleSearch.vue";


const load_num = 12;
const resultsPerQuery = 12;
const perPageText = 'per_page=' + resultsPerQuery;
// exclude Gluck Category, only top level pages, order by title asc

const baseUrl = "/wp-json/wp/v2/ensembles/?" + perPageText + "&order=asc&orderby=title&parent=0&ensembles_category_exclude=348";
const ensemblesCategoriesListUrl = "/wp-json/wp/v2/ensembles_category/?" + perPageText + "&exclude=348";
const fields = "&fields=title.rendered,link,acf.sections,acf.options,acf.alternative_thumb";
const filterByCat = "/wp-json/wp/v2/ensembles/?" + perPageText + "&order=asc&orderby=title&parent=0&ensembles_category_exclude=348&ensembles_category=";

var ES6Promise = require("es6-promise");
ES6Promise.polyfill();
Vue.prototype.$http = axios;

export default {
	name: "EnsemblesDirectory",
	components: {
		EnsembleCategory,
		EnsembleCard,
		EnsembleSearch,
	},
	data() {
		return {
			curPage: 1, // Pagination helper
			currentUrl: baseUrl,
			chosenCategory: "",
			items: [],
			loading: false,
			results: [],
            categories: [],
			categoryValue: "All",
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
			this.items = [];
			this.results = [];
			this.currentQuery = customUrl;
			customUrl = customUrl + fields;
			axios
				.get(customUrl)
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
			axios
				.get(ensemblesCategoriesListUrl)
				.then(response => {
					this.categories = response.data;
				})
				.catch(response => {
					console.log(response);
				});
		},
		searchTerm(term) {
			this.currentUrl = this.currentUrl + '&search=' + term;
			this.getResults(this.currentUrl);
		},
		onChooseCategory(item) {
			this.curPage = 1;
            this.categoryValue = item.name;
			this.chosenCategory = item.id;
			this.currentUrl = filterByCat + item.id;
			this.getResults(this.currentUrl);
		},
		resetCategory(){
			this.categoryValue = null;
			this.getResults();
		},
	},
	computed: {
		noMoreItems: function() {
			return this.items.length === this.totalResults && this.results.length > 0;
        },
        selectedValue: function() {
            return this.categoryValue || 'All';
        }
	},
	mounted() {
		this.getResults();
		this.getCategories();
		let self = this;
		var elem = document.getElementById('ensemble-list-bottom');
		var watcher = scrollMonitor.create(elem);
		watcher.enterViewport(function() {
			self.appendItems();
		});
	},
};
</script>
