<template>
  <div
    class="py-4 px-4 -mx-4 border-b border-offwhite last:border-b-0"
    @mouseenter="handleMouseEnter()"
    @mouseleave="handleMouseLeave()"
  >
    <router-link :to="{ name: 'post', params: { id: this.post.id } }" class="flex flex-row">
      <div class="w-full">
        <h2
          class="mb-2 font-medium w-full text-lg"
          :class="[currentHoverPostId == post.id ? 'text-blue-500' : 'text-black']"
        >
          <slot name="title">{{ post.title }}</slot>
        </h2>

        <div class="mb-2 w-full text-sm font-light text-justify break-all">
          <slot name="body">{{ post.summary }}</slot>
        </div>
        <component
          class="mb-2"
          v-if="post.cover_media"
          :is="post.cover_media.type+'-media'"
          :media="post.cover_media"
        ></component>

        <tags :tags="post.tags"></tags>
        <div class="text-xs font-light text-grey mt-1">
          <span>发布于{{ post.created_at_human }}</span>
          <span>·</span>
          <span>更新于{{ post.updated_at_human }}</span>
          <span>·</span>
          <span>{{ post.visited_count }}阅读</span>
        </div>
      </div>
    </router-link>
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
    }
  }
};
</script>
