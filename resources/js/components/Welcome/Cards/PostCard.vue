<template>
  <div class="px-4 pb-8">
    <div class="rounded shadow hover:shadow-xl custom__post-card">
      <div v-if="post.cover_media">
        <component :is="post.cover_media.type+'-media'" :media="post.cover_media"></component>
      </div>

      <div class="relative">
        <div
          class="absolute text-xs text-primary mr-2 mt-1 rotate-45"
          v-if="post.is_top"
          style="right:0;top:-2px"
        >
          <i class="fas fa-thumbtack"></i>
        </div>

        <a
          :href="'/posts/' + post.id"
          target="_blank"
          class="absolute top-0 left-0 z-10 w-full h-full"
        ></a>

        <div class="px-4 py-2 text-lg">{{ post.title }}</div>

        <div class="flex text-xs px-4" v-if="post.tags.length">
          <div class="mr-1">
            <i class="fas fa-tags"></i>
          </div>
          <tags :tags="post.tags"></tags>
        </div>

        <div class="flex items-center justify-between px-4 py-2 text-xs text-gray-600">
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
    </div>
  </div>
</template>

<script>
import ImageMedia from "./CoverMedia/ImageMedia";
import VideoMedia from "./CoverMedia/VideoMedia";
import AudioMedia from "./CoverMedia/AudioMedia";

export default {
  props: ["post", "width"],

  components: {
    ImageMedia,
    VideoMedia,
    AudioMedia
  },

  mounted() {
    console.log(this.post);
  }
};
</script>

<style lang="scss">
.custom__post-card {
  transition: 0.6s box-shadow;
}
</style>
