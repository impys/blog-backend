<template>
  <div class="flex items-center justify-center">
    <div v-bind:style="{ paddingBottom: aspectRatio }"></div>
    <a :href="'/posts/' + post.id">
      <img :src="media.url" class="rounded-t" />
    </a>
  </div>
</template>
<script>
export default {
  props: ["post"],

  data() {
    return {
      media: this.post.cover_media
    };
  },

  computed: {
    aspectRatio() {
      return Math.round((this.media.height / this.media.width) * 100, 2) + "%";
    }
  }
};
</script>
