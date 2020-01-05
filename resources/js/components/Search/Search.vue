<template>
  <main-layout>
    <template v-slot:header>
      <div class="flex flex-row items-center w-full h-8 bg-offwhite rounded-full">
        <div class="w-8">
          <svg class="icon block mx-2 text-ching" key="unloading">
            <use xlink:href="#icon-search-outline" />
          </svg>
        </div>
        <div class="flex items-center w-full">
          <input
            v-focus
            type="text"
            ref="searchInput"
            v-model="query"
            @keyup.enter="search"
            class="outline-none w-full border-transparent text-base bg-transparent w-full z-10 input-caret-color-ching"
          />
        </div>
        <div class="w-8">
          <svg v-if="query.length" @click="reset()" class="icon text-grey cursor-pointer">
            <use xlink:href="#icon-close-outline" />
          </svg>
        </div>
      </div>
      <div class="w-10 text-sm text-ching font-normal ml-2 cursor-pointer" @click="goBack">取消</div>
    </template>
    <template v-slot:content>TODO:展示搜索结果</template>
    <template v-slot:sidebar-content></template>
    <template v-slot:sidebar-header>
      <div></div>
    </template>
  </main-layout>
</template>


<script>
import * as api from "../../api/Search";

export default {
  async beforeRouteEnter(to, from, next) {
    if (!to.query.query) {
      return next();
    }
    try {
      const response = await api.search(to.query.query);
      next(vm => vm.setResults(response));
    } catch (error) {
      return next(false);
    }
  },

  data() {
    return {
      searchLoading: false,
      posts: [],
      meta: null,
      query: this.$route.query.query || ""
    };
  },

  methods: {
    async search() {
      if (!this.query.trim()) {
        return;
      }

      // TODO:cancel last request by cancel token
      if (this.searchLoading) {
        return;
      }

      this.start();

      try {
        const response = await api.search(this.query);
        this.setResults(response);
      } catch (error) {
        console.log(error);
      }

      this.finished();
    },

    setResults(response) {
      this.posts = response.data;
      this.meta = response.meta;
    },

    goBack() {
      this.$router.go(-1);
    }
  }
};
</script>
