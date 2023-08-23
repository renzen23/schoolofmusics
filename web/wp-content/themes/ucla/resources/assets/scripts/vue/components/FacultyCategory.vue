<template>
    <li ref="li">
        <a href="" v-on:click.prevent="chooseCategory(item)">{{ item.name }}</a>
    </li>
</template>

<script>

export default {
    name: "FacultyCategory",
    props: {
        item: {
            type: Object,
        },
        loading: {
            type: Boolean,
        },
    },
    data() {
        return {
            clicked: false,
        }
    },
    mounted() {
        let hash = location.hash;
        console.log(hash);
        if ( hash == ('#' + this.item.slug) ) {
            this.chooseCategory(this.item);
            window.scrollTo(0, document.getElementById('faculty-listing').getBoundingClientRect().top)
        }


    },
    methods: {
        chooseCategory(item) {
            this.$emit("chooseCategory", item);
        }
    },
    computed: {
        
    },
    created() {
        
    },
    watch: {
        loading() {
            if ( !this.loading && !this.clicked ) {
                if ( location.hash && location.hash.includes(this.item.taxonomy + '=' + this.item.slug) ) {
                    console.log('clicked ' + this.item.slug);
                    this.chooseCategory(this.item);
                    this.clicked = true;
                }
            }
        }
    }
};
</script>
