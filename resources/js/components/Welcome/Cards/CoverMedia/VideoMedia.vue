<template>
  <div class="flex items-center justify-center">
    <div v-bind:style="{ paddingBottom: aspectRatio }"></div>
    <video
      preload="metadata"
      oncontextmenu="return false"
      controlslist="nodownload"
      :poster="media.poster.url"
      class="rounded-t"
      @mouseenter="handleMouseEnter()"
      @mouseleave="handleMouseLeave()"
      :id="'video-' + media.id"
    >
      <source :src="media.url" />
    </video>
  </div>
</template>
<script>
export default {
  props: ["media"],

  computed: {
    aspectRatio() {
      return (
        Math.round(
          (this.media.poster.height / this.media.poster.width) * 100,
          2
        ) + "%"
      );
    }
  },

  methods: {
    handleMouseEnter() {
      this.toggleVideoControls();
    },

    handleMouseLeave() {
      this.toggleVideoControls();
    },

    /**
     * show or hidden this video control panel by dom id
     */
    toggleVideoControls(postId) {
      let id = "#video-" + this.media.id;
      let video = document.querySelector(id);
      if (video.hasAttribute("controls")) {
        video.removeAttribute("controls");
      } else {
        video.setAttribute("controls", "controls");
      }
    }
  }
};
</script>
