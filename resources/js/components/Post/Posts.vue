<template>
  <div class="flex flex-wrap">
    <post v-for="post in posts" :key="post.id" :post="post"></post>
  </div>
</template>


<script>
import Post from "./Post";
export default {
  props: ["posts"],

  components: {
    Post
  }
};
</script>
