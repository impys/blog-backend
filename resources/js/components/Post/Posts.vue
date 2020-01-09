<template>
  <main-layout>
    <template v-slot:header>
      <h1 class>文章</h1>
    </template>
    <template v-slot:content>
      <div v-scroll="handleScroll">
        <post-card v-for="(post,index) in posts" :key="index" :post="post"></post-card>
        <div v-if="isLastPage" class="text-center text-sm text-grey my-4">到底了</div>
      </div>
    </template>
  </main-layout>
</template>

<script>
import * as api from "../../api/GetPosts";

export default {
  name: "posts",
  data() {
    return {
      posts: [],
      meta: null,
      loading: false
    };
  },

  computed: {
    isLastPage: function() {
      return helper.isLastPageByMeta(this.meta);
    },
    currentPage: function() {
      return helper.getCurrentByMeta(this.meta);
    }
  },

  async beforeRouteEnter(to, from, next) {
    EventHub.$emit("resetSearch");
    next();
  },

  mounted() {
    this.get();
  },

  methods: {
    async get() {
      if (this.prevent()) {
        return;
      }

      this.start();

      try {
        const response = await api.get(this.currentPage);
        this.handleResponse(response);
      } catch (error) {
        console.log(error);
      }

      this.finished();
    },

    prevent() {
      return this.loading;
    },

    start() {
      this.loading = true;
    },

    handleResponse(response) {
      this.posts.push(...response.data);
      this.meta = response.meta;
    },

    finished() {
      this.loading = false;
    },

    handleScroll() {
      return helper.handleScroll(this);
    }
  }
};
</script>
