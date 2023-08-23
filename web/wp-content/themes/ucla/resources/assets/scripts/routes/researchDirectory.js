import Vue from 'vue';
import ResearchDirectory from '../vue/ResearchDirectory.vue';

export default {
    init() {
        // Vue.relatedList = false;
        // JavaScript to be fired on all pages
        new Vue({
            components: {
                ResearchDirectory,
            },
            el: '#research-listing',
        });
    },
};
