<template>
  <div class="break-all">
    <h2 class="mb-1 text-sm lg:text-base" v-html="post.title"></h2>
    <div class="text-xs" v-html="post.body"></div>
  </div>
</template>

<script>
export default {
  props: ["post"]
};
</script>
