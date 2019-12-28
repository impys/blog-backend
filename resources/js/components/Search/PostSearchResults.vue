<template>
  <div class="relative py-2 w-full text-black border-b-2 border-white">
    <!-- <a :href="'/posts/' + post.id" class="absolute top-0 left-0 z-10 w-full h-full"></a> -->
    <a :href="'/posts/' + post.id" class="block mb-1" v-html="post.title"></a>
    <div class="text-sm" v-html="post.body"></div>
    <div class="text-xs text-grey">{{ post.updated_at_human }}</div>
    <tags :tags="post.tags" v-if="post.tags.length"></tags>
  </div>
</template>

<script>
export default {
  props: ["post"]
};
</script>
