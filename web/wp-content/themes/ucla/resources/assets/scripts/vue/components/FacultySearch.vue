<template>
    <!-- <div> -->
    <form class="searchbar" @keyup.prevent="onSubmit" @keydown.enter.prevent="onSubmit">
        <img src="/app/themes/ucla/resources/assets/images/search.svg" width="16px" height="16px" class="search-icon svg" alt="Search Faculty">
        <div class="sr-only">Faculty Search</div>
        <input v-model="newSearch" type="text" placeholder="Search..." aria-labelledby="Search Faculty">
    </form>
    <!-- </div> -->
</template>

<script>

export default {
    name: "FacultySearch",
    data() {
        return {
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
