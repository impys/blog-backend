<template>
  <div
    class="flex flex-row rounded cursor-pointer hover:bg-offwhite transition-background-color-03"
  >
    <div class="mr-2">
      <img src="img/logo.png" width="40px" height="40px" class="rounded-full bg-white" />
    </div>
    <div class="w-full">
      <div class="mb-2">
        <span class="text-sm font-medium text-ching">青风百里</span>
        <span class="text-xs font-light text-grey">· {{ post.updated_at_human }}</span>
      </div>

      <div @mouseenter="handleMouseEnter(post.id)" @mouseleave="handleMouseLeave()">
        <router-link :to="'/posts/'+post.id">
          <h2
            class="mb-2 font-light w-full text-xl"
            :class="[currentHoverPostId == post.id ? 'text-ching' : 'text-black']"
          >{{ post.title }}</h2>
          <div class="mb-2 w-full text-sm text-grey text-justify break-all">{{ post.summary }}</div>
        </router-link>
      </div>

      <component
        class="mb-2"
        v-if="post.cover_media"
        :is="post.cover_media.type+'-media'"
        :media="post.cover_media"
      ></component>

      <tags :tags="post.tags"></tags>
    </div>
  </div>
</template>

<script>
import ImageMedia from "./Media/ImageMedia";
import AudioMedia from "./Media/AudioMedia";

export default {
  props: ["post"],

  data() {
    return {
      currentHoverPostId: null
    };
  },

  components: {
    ImageMedia,
    AudioMedia
  },

  methods: {
    handleMouseEnter(postId) {
      this.currentHoverPostId = postId;
    },
    handleMouseLeave() {
      this.currentHoverPostId = null;
    }
  }
};
</script>
