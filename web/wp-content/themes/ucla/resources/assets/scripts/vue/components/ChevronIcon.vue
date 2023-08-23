<template>
  <svg
    version="1.1"
    id="Layer_1"
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    x="0px"
    y="0px"
    viewBox="0 0 17 9.5"
    style="enable-background:new 0 0 17 9.5;"
    xml:space="preserve"
  >
    <path
      class="st0"
      :style="{ fill }"
      d="M9.2,9.1c-0.4,0.4-1,0.4-1.4,0L0.4,1.8C0,1.4,0,0.8,0.4,0.4c0.4-0.4,1-0.4,1.4,0l6.7,6.7l6.7-6.7
	c0.4-0.4,1-0.4,1.4,0c0.4,0.4,0.4,1,0,1.4L9.2,9.1z"
    />
  </svg>
</template>

<script>
export default {
  props: {
    fill: {
      type: String,
      default: "#3183be"
    }
  }
};
</script>
