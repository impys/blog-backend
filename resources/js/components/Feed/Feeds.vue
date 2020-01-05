<template>
  <main-layout>
    <template v-slot:header>
      <h1 class="px-4">文章</h1>
    </template>
    <template v-slot:content>
      <feed class="p-4" v-for="(post,index) in posts" :key="index" :post="post"></feed>
    </template>
  </main-layout>
</template>

<script>
import * as api from "../../api/GetPosts";
import Feed from "./Feed";

export default {
  components: {
    Feed
  },

  data() {
    return {
      posts: [],
      meat: {}
    };
  },

  beforeRouteEnter(to, from, next) {
    EventHub.$emit("resetSearch");
    next();
  },

  mounted() {
    this.get();
  },

  methods: {
    async get() {
      try {
        const response = await api.get();
        this.posts.push(...response.data);
        this.meta = response.meta;
      } catch (error) {}
    }
  }
};
</script>
