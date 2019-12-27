<template>
  <div class="custom__masonry -mx-2 lg:-mx-3">
    <post-card v-for="post in posts.data" :key="post.id" :post="post" class="custom__masonry-item"></post-card>
  </div>
</template>


<script>
import PostCard from "./PostCard";
export default {
  props: ["posts"],

  components: {
    PostCard
  }
};
</script>

<style lang="scss">
.custom__masonry {
  column-count: 2;
  column-gap: 0;
  counter-reset: item-counter;

  //   @media screen and (min-width: 300px) {
  //     column-count: 2;
  //   }

  //   @media screen and (min-width: 768px) {
  //     column-count: 2;
  //   }

  @media screen and (min-width: 1024px) {
    column-count: 3;
  }

  @media screen and (min-width: 1400px) {
    column-count: 4;
  }
}

.custom__masonry-item {
  box-sizing: border-box;
  break-inside: avoid;
  counter-increment: item-counter;
}
</style>
