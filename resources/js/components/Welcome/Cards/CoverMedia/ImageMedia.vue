<template>
  <div class="flex items-center justify-center">
    <div v-bind:style="{ paddingBottom: aspectRatio }"></div>
    <img :src="media.url" class="rounded-t" />
  </div>
</template>
<script>
export default {
  props: ["media"],

  computed: {
    aspectRatio() {
      return Math.round((this.media.height / this.media.width) * 100, 2) + "%";
    }
  }
};
</script>
