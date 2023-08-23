<template>
    <!-- <div> -->
    <form class="searchbar" @keyup.prevent="onSubmit" @submit.prevent="falseSubmit">
        <img src="/app/themes/ucla/resources/assets/images/search.svg" width="16px" height="16px" class="search-icon svg" alt="Search Research">
        <div class="sr-only">Research Search</div>
        <input v-model="newSearch" type="text" placeholder="Search..." aria-labelledby="Search Research">
    </form>
    <!-- </div> -->
</template>

<script>

export default {
    name: "research-search",
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
        falseSubmit() {
            return false;
        },
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
