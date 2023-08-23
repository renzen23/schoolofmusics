import Vue from 'vue';
import FacultyDirectory from '../vue/FacultyDirectory.vue';

export default {
	init() {
		// Vue.relatedList = false;
		// JavaScript to be fired on all pages
		new Vue({
			components: {
				FacultyDirectory,
			},
			el: '#faculty-listing',
		});
	},
};
