<template>
  <div class="mx-2 pb-4 lg:mx-3 lg:pb-6">
    <div class="rounded border border-black hover:shadow-focus transition-box-shadow-05">
      <a v-if="post.cover_media" :href="'/posts/' + post.id">
        <component :is="post.cover_media.type+'-media'" :media="post.cover_media"></component>
      </a>
      <!-- <div v-if="post.cover_media">
        <component :is="post.cover_media.type+'-media'" :media="post.cover_media"></component>
      </div>-->

      <div class="relative bg-white rounded">
        <div
          class="absolute text-xs text-pink mr-2 mt-1 rotate-45"
          v-if="post.is_top"
          style="right:-4px;bottom:-1px"
        >
          <i class="fas fa-thumbtack"></i>
        </div>

        <a :href="'/posts/' + post.id" class="absolute top-0 left-0 z-10 w-full h-full"></a>

        <h2
          class="px-4 pt-2 pb-1 text-black w-full text-sm md:text-base lg:text-base"
        >{{ post.title }}</h2>

        <div class="px-4 pb-1 text-black w-full text-xs">{{ post.summary }}</div>

        <div
          class="flex flex-row-reverse items-center justify-between px-4 pb-2 pt-1 text-xs text-grey"
        >
          <div class="flex">{{ post.updated_at_human }}</div>
          <div v-if="post.tags.length">
            <tags :tags="post.tags.slice(0,2)"></tags>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ImageMedia from "./CoverMedia/ImageMedia";
import AudioMedia from "./CoverMedia/AudioMedia";

export default {
  props: ["post"],

  components: {
    ImageMedia,
    AudioMedia
  }
};
</script>
