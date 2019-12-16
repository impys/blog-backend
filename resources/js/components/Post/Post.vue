<template>
  <div class="px-4">
    <h1 class="text-5xl my-6">{{ post.title }}</h1>
    <div class="text-gray-600 my-6">{{ post.updated_at_human }}</div>
    <div v-html="markedBody"></div>
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
