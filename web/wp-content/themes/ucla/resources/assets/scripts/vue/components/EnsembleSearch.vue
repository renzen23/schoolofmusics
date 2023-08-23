<template>
    <!-- <div> -->
    <form class="searchbar" @keyup.prevent="onSubmit">
        <img src="/app/themes/ucla/resources/assets/images/search.svg" width="16px" height="16px" class="search-icon svg" alt="Search Ensembles">
        <div class="sr-only">Ensembles Search</div>
        <input v-model="newSearch" type="text" placeholder="Search..." aria-labelledby="Search Ensembles">
    </form>
    <!-- </div> -->
</template>

<script>

export default {
    name: "ensemble-search",
    data() {
        return {
            lastSearch: '',
            newSearch: '',
        }
    },
    props: {
        item: {
            type: Object,
        },
    },
    methods: {
        onSubmit() {
            let self = this;

            if (this.timer) {
                clearTimeout(this.timer);
                this.timer = null;
            }

            this.timer = setTimeout(() => {
                this.$emit("triggerSearch", self.newSearch);
            }, 800);
        }
    },
    computed: {

    },
    created() {

    },
};
</script>
