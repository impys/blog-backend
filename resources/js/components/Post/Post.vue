<template>
  <div class="px-4 pb-8">
    <div class="rounded border border-gray-700 hover:shadow-lg">
      <a
        class="block"
        :href="'/posts/' + post.id"
        v-if="post.cover && !post.first_video"
        id="custom_cover-image"
      >
        <img
          :src="post.cover"
          :alt="post.title"
          v-if="post.cover"
          class="rounded-t w-full"
          @load="hanldeImageLoad"
        />
      </a>

      <div class="relative">
        <div
          class="absolute text-xs text-primary mr-2 mt-1 rotate-45"
          v-if="post.is_top"
          style="right:0;top:-2px"
        >
          <i class="fas fa-thumbtack"></i>
        </div>

        <a :href="'/posts/' + post.id" class="absolute top-0 left-0 z-10 w-full h-full"></a>

        <div class="mx-4 my-2">{{ post.title }}</div>

        <div class="flex text-xs mx-4 my-2" v-if="post.tags.length">
          <div class="mr-1">
            <i class="fas fa-tags"></i>
          </div>
          <tags :tags="post.tags"></tags>
        </div>

        <div class="flex items-center justify-between mx-4 my-2 text-xs text-gray-600">
          <div class="mr-2">{{ post.updated_at_human }}</div>
          <!-- <div class="flex">
          <div class="mr-2">
            <i class="far fa-heart"></i>
            {{ post.visited_count }}
          </div>
          <div class="mr-2">
            <i class="far fa-eye"></i>
            {{ post.upvote_count }}
          </div>
          </div>-->
        </div>
      </div>

      <div
        @mouseenter="handleMouseEnter()"
        @mouseleave="handleMouseLeave()"
        v-html="post.first_video"
        v-if="post.first_video"
        class="rounded-t"
        :id="'video-'+post.id"
      ></div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["post"],

  data() {
    return {};
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
      let id = "#video-" + this.post.id;
      let video = document.querySelector(id).childNodes[0];
      if (video.hasAttribute("controls")) {
        video.removeAttribute("controls");
      } else {
        video.setAttribute("controls", "controls");
      }
    },

    hanldeImageLoad(e) {
      let image = e.path[0];
      if (image.complete) {
        let customCoverImage = document.querySelector("#custom_cover-image");
        let clientHeight = this.calculateImageClientHeight(image);
        customCoverImage.style.maxHeight = clientHeight + "px";
        customCoverImage.style.opacity = 1;
      }
    },

    calculateImageClientHeight(image) {
      let clientWidth = image.clientWidth;
      let naturalHeight = image.naturalHeight;
      let naturalWidth = image.naturalWidth;
      let clientHeight = (naturalHeight / naturalWidth) * clientWidth;
      return clientHeight;
    }
  }
};
</script>

<style lang="scss">
#custom_cover-image {
  max-height: 0;
  opacity: 0;
  transition: 0.5s max-height ease-in, 0.5s opacity ease-in;
}
</style>
