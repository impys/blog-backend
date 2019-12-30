<template>
  <div class="relative py-2 w-full text-black border-b-2 border-white break-all">
    <a :href="'/posts/' + post.id" class="absolute top-0 left-0 z-10 w-full h-full"></a>
    <h2 class="mb-1 text-sm md:text-base lg:text-base" v-html="post.title"></h2>
    <div class="text-xs" v-html="post.body"></div>
  </div>
</template>

<script>
export default {
  props: ["post"]
};
</script>
