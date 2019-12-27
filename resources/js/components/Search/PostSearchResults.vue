<template>
  <div class="flex flex-col w-full">
    <div class="relative py-2 w-full text-black">
      <!-- <a :href="'/posts/' + post.id" class="absolute top-0 left-0 z-10 w-full h-full"></a> -->
      <a :href="'/posts/' + post.id" class="block mb-2 hover:text-ching" v-html="post.title"></a>
      <div class="text-sm" v-html="post.body"></div>
      <!-- <tags :tags="post.tags" v-if="post.tags.length"></tags> -->
    </div>
  </div>
</template>

<script>
export default {
  props: ["post"]
};
</script>
