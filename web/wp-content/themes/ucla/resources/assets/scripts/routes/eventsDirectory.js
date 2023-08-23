import Vue from 'vue';
import VueRouter from 'vue-router';
import EventsDirectory from '../vue/EventsDirectory.vue';

export default {
	init() {
		// Vue.relatedList = false;
		Vue.use(VueRouter);
		const router = new VueRouter({
			routes: [ { path: '/cat/:category' }, { path: '/special-program/:program' } ],
			linkActiveClass: 'active',
		});

		new Vue({
			components: {
				EventsDirectory,
			},
			router,
			el: '#listing',
		});
	},
};
