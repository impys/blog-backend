<template>
  <main-layout>
    <template v-slot:header>
      <h1 class="px-4">文章</h1>
    </template>
    <template v-slot:content>
      <div v-scroll="handleScroll">
        <feed class="p-4" v-for="(post,index) in posts" :key="index" :post="post"></feed>
        <div v-if="noMore" class="text-center text-sm text-grey my-2">到底了</div>
      </div>
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
      meta: null,
      loading: false
    };
  },

  computed: {
    noMore: function() {
      return this.meta && this.meta.current_page == this.meta.last_page;
    },
    currentPage: function() {
      if (!this.meta) {
        return 1;
      }

      if (this.meta.current_page == this.meta.last_page) {
        return this.meta.last_page;
      }
      return this.meta.current_page + 1;
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

    handleResponse(response) {
      this.posts.push(...response.data);
      this.meta = response.meta;
    },

    prevent() {
      return this.loading || this.noMore;
    },

    start() {
      this.loading = true;
    },
    finished() {
      this.loading = false;
    },

    handleScroll(evt, el) {
      let scrollBottom = this.getScrollBottom();
      if (scrollBottom <= 300) {
        this.get();
      }
    },

    getScrollBottom() {
      let offsetHeight = Math.max(
        document.body.scrollHeight,
        document.body.offsetHeight
      );
      let clientHeight =
        window.innerHeight ||
        document.documentElement.clientHeight ||
        document.body.clientHeight ||
        0;
      let scrollTop =
        window.pageYOffset ||
        document.documentElement.scrollTop ||
        document.body.scrollTop ||
        0;

      return offsetHeight - clientHeight - scrollTop;
    }
  }
};
</script>
