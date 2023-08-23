<template>
  <img :src="glyph" :class="className" :width="width" :height="height" />
</template>

<script>
export default {
  name: 'icon',
  props: ['className', 'glyph', 'width', 'height'],
};
</script>
