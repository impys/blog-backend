<template>
  <div
    class="flex flex-row rounded p-4 cursor-pointer hover:bg-offwhite transition-background-color-03"
    @click="handleClick()"
    @mouseenter="handleMouseEnter()"
    @mouseleave="handleMouseLeave()"
  >
    <div class="mr-2">
      <img src="img/logo.png" width="40px" height="40px" class="rounded-full bg-white border border-gray-200" />
    </div>
    <div class="w-full">
      <div class="mb-2">
        <span class="text-xs font-medium text-black">青风百里</span>
        <span class="text-xs font-light text-grey">· {{ post.updated_at_human }}</span>
      </div>
      <h2
        class="mb-2 font-normal w-full text-lg"
        :class="[currentHoverPostId == post.id ? 'text-blue-500' : 'text-black']"
      >
        <slot name="title">{{ post.title }}</slot>
      </h2>

      <div class="mb-2 w-full text-sm text-grey text-justify break-all">
        <slot name="body">{{ post.summary }}</slot>
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
import ImageMedia from "./PostCoverMedia/ImageMedia";
import AudioMedia from "./PostCoverMedia/AudioMedia";

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
    handleMouseEnter() {
      this.currentHoverPostId = this.post.id;
    },
    handleMouseLeave() {
      this.currentHoverPostId = null;
    },
    handleClick() {
      this.$router.push({ name: "post", params: { id: this.post.id } });
    }
  }
};
</script>
