<template>
  <div>
    <!-- <div class="border-b py-2 mb-4 sticky top-12 bg-white z-20">
      <orders></orders>
      <tags :initialTags="meta.tags"></tags>
    </div>-->
    <post-card :post="post" v-for="(post,index) in posts" :key="index">
      <template v-slot:title>
        <span v-html="post.title"></span>
      </template>
      <template v-slot:body>
        <span v-html="post.body"></span>
      </template>
    </post-card>
  </div>
</template>

<script>
import Orders from "./Orders";
import Tags from "./Tags";

export default {
  props: ["data", "meta"],

  components: { Orders, Tags },

  data() {
    return {
      posts: this.data
    };
  }
};
</script>
