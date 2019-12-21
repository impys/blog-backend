<template>
  <div class="custom__masonry">
    <post-card v-for="post in posts" :key="post.id" :post="post" class="custom__masonry-item"></post-card>
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

  //   @media screen and (min-width: 1024px) {
  //     column-count: 2;
  //   }

  @media screen and (min-width: 1280px) {
    column-count: 3;
  }
}

.custom__masonry-item {
  box-sizing: border-box;
  break-inside: avoid;
  counter-increment: item-counter;
}
</style>
