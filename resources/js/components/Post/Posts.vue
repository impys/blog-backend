<template>
  <main-layout>
    <template v-slot:header>
      <h1>
        文章
        <span class="font-normal text-blue-500" v-if="tag_name">#{{tag_name}}</span>
      </h1>
    </template>
    <template v-slot:content>
      <div v-scroll="handleScroll">
        <post-card v-for="(post,index) in posts" :key="index" :post="post"></post-card>
      </div>
      <div v-if="isLastPage" class="text-center text-sm text-grey my-4">到底了</div>
    </template>
  </main-layout>
</template>

<script>
import * as api from "../../api/GetPosts";

export default {
  name: "posts",

  props: ["tag_id", "tag_name"],

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

  watch: {
    tag_id(newTagId, oldTagId) {
      this.reGet();
    }
  },

  mounted() {
    this.get();
  },

  methods: {
    reGet() {
      this.emptyPosts();
      this.get();
    },
    async get() {
      if (this.loading) {
        return;
      }

      this.loading = true;

      try {
        const response = await api.get(this.currentPage, this.tag_id);
        this.handleResponse(response);
      } catch (error) {
        console.log(error);
      }

      this.loading = false;
    },

    handleResponse(response) {
      this.posts.push(...response.data);
      this.meta = response.meta;
    },

    emptyPosts() {
      this.posts = [];
      this.meta = null;
    },

    handleScroll() {
      return helper.handleScroll(this);
    }
  }
};
</script>
