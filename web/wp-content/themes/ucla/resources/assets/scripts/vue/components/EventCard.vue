<template>
  <div class="event-card col-md-6 col-lg-4 mb-5">
    <a :href="event.url">
      <div class="image-container">
        <img :src="event.image" :alt="event.title" />
      </div>
    </a>
    <div class="position-relative">
      <p class="category text-uppercase text-primary">
        {{ event.category ? event.category : "-" }}
      </p>
      <h3 class="event-card-title">
        <a class="text-darkgrey" :href="event.url" v-html="event.title"></a>
      </h3>
      <div class="event-date">
        <span class="date-month">{{ event.date.month }}</span>
        <span class="date-number">{{ event.date.date }}</span>
        <span class="date-text">{{ event.date.day }}</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "EventCard",

  props: {
    event: {
      type: Object,
      required: true
    }
  }
};
</script>
