<template>
  <main-layout>
    <template v-slot:header>
      <h1>
        文章
        <span class="font-normal text-blue-500" v-if="tagName">#{{tagName}}</span>
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
  data() {
    return {
      posts: [],
      meta: null,
      loading: false,
      tagId: null,
      tagName: null
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

  beforeRouteEnter(to, from, next) {
    //刷新搜索页面的时候，如果地址栏中有关键词，应该拿下来，放到keyword中，触发搜索
    next(vm => {
      vm.setTagIdAndName(to.query.tag_id || null, to.query.tag_name || null);
      vm.reGet();
    });
  },

  watch: {
    tagId(newTagId, oldTagId) {
      this.replaceRouteByTagId(newTagId);
      this.get();
    },
    //TODO:重构通过监听路由实现重新加载
    $route(to, from) {
      this.setTagIdAndName(to.query.tag_id || null, to.query.tag_name || null);
      this.reGet();
    }
  },

  methods: {
    reGet() {
      this.emptyPosts();
      this.get();
    },
    async get() {
      if (this.prevent()) {
        return;
      }

      this.start();

      try {
        const response = await api.get(this.currentPage, this.tagId);
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

    emptyPosts() {
      this.posts = [];
      this.meta = null;
    },

    setTagIdAndName(tagId, tagName) {
      this.tagId = tagId;
      this.tagName = tagName;
    },

    handleScroll() {
      return helper.handleScroll(this);
    },

    replaceRouteByNewQueryIfNeeded(newQuery) {
      let query = this.getConcatedQuery(newQuery);
      if (!_.isEqual(this.$route.query, query)) {
        this.$router.replace({ query: query });
      }
    },
    getConcatedQuery(newQuery) {
      return _.pickBy(_.assign({}, this.$route.query, newQuery), OoO => OoO);
    },
    replaceRouteByTagId(newTagId) {
      this.replaceRouteByNewQueryIfNeeded({ tag_id: newTagId });
    }
  }
};
</script>
