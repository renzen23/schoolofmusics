<template>
    <li>
        <a href="" v-on:click.prevent="chooseCategory(item)">{{ item.name }}</a>
    </li>
</template>

<script>

export default {
    name: "ResearchCategory",
    props: {
        item: {
            type: Object,
        },
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
};
</script>
