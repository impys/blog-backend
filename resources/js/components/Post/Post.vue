<template>
  <div class="px-4 pb-8">
    <div class="rounded border border-gray-700 hover:shadow-lg">
      <a
        class="block flex items-center justify-center"
        :href="'/posts/' + post.id"
        v-if="post.cover && !post.first_video"
      >
        <div v-bind:style="{ paddingBottom: post.cover_aspect_ratio }"></div>
        <img :src="post.cover" :alt="post.title" v-if="post.cover" class="rounded-t w-full" />
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
  props: ["post", "width"],

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
    }
  }
};
</script>
