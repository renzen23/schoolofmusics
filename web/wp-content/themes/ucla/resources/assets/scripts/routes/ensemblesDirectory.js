import Vue from 'vue';
import EnsemblesDirectory from '../vue/EnsemblesDirectory.vue';

export default {
	init() {
		// Vue.relatedList = false;
		// JavaScript to be fired on all pages
		new Vue({
			components: {
				EnsemblesDirectory,
			},
			el: '#listing',
		});
	},
};
