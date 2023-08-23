<template>
    <li>
        <a href="" v-on:click.prevent="chooseYear(item)">{{ item }}</a>
    </li>
</template>

<script>

export default {
    name: "ResearchYear",
    props: {
        item: {
            type: Number,
        },
    },
    methods: {
        chooseYear(item) {
            this.$emit("chooseYear", item);
        }
    },
    computed: {

    },
    created() {

    },
};
</script>
