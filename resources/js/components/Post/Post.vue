<template>
  <div class="px-4">
    <h1 class="text-5xl mb-6 ml-auto text-black">{{ post.title }}</h1>
    <div class="text-grey my-6">创建于{{ post.created_at_human }} / 更新于{{ post.updated_at_human }}</div>
    <div class="markdown-body" v-html="markedBody"></div>
  </div>
</template>

<script>
import marked from "marked";

export default {
  props: ["post"],
  computed: {
    markedBody: function() {
      return marked(this.post.body);
    }
  }
};
</script>
