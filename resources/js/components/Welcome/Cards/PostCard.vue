<template>
  <div class="px-4 pb-8">
    <div class="rounded shadow hover:shadow-xl custom__post-card">
      <div v-if="post.cover_media">
        <component :is="post.cover_media.type+'-media'" :media="post.cover_media"></component>
      </div>

      <div class="relative">
        <div
          class="absolute text-xs text-pink mr-2 mt-1 rotate-45"
          v-if="post.is_top"
          style="right:0;bottom:3px"
        >
          <i class="fas fa-thumbtack"></i>
        </div>

        <a
          :href="'/posts/' + post.id"
          target="_blank"
          class="absolute top-0 left-0 z-10 w-full h-full"
        ></a>

        <div class="px-4 pt-2 pb-1 text-base">{{ post.title }}</div>

        <div class="px-4" v-if="post.tags.length">
          <tags :tags="post.tags"></tags>
        </div>

        <div class="flex items-center justify-between px-4 pb-2 pt-1 text-xs text-grey">
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
import AudioMedia from "./CoverMedia/AudioMedia";

export default {
  props: ["post", "width"],

  components: {
    ImageMedia,
    AudioMedia
  }
};
</script>

<style lang="scss">
.custom__post-card {
  transition: 0.6s box-shadow;
}
</style>
