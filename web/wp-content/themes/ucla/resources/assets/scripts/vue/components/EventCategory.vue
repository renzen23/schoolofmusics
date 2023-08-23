<template>
  <router-link tag="li" :to="customLink">
    <a>{{ item.name }}</a>
  </router-link>
</template>

<script>
export default {
  name: "EventCategory",
  props: {
    index: {
      type: Number
    },
    item: {
      type: Object
    },
    route: {
      type: String,
      required: true
    }
  },
  methods: {
    chooseCategory(item, index) {
      this.$emit("chooseCategory", item, index);
    }
  },
  computed: {
    customLink() {
      return `/${this.route}/${this.item.slug}`;
    }
  }
};
</script>
