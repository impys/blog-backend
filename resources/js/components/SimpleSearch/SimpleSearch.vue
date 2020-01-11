<template>
  <div class="relative w-full h-10">
    <div
      class="flex flex-col w-full max-h-vh-45 border border-blue-500 rounded bg-white absolute top-0"
    >
      <div class="flex flex-row items-center min-h-7">
        <div class="w-8">
          <transition name="slide-fade" mode="out-in">
            <svg class="icon block mx-2 text-blue-500" v-if="!loading" key="unloading">
              <use xlink:href="#icon-search-outline" />
            </svg>

            <loading-icon key="loading" v-if="loading"></loading-icon>
          </transition>
        </div>
        <div class="flex items-center w-full">
          <input
            type="text"
            ref="simpleSearchInput"
            placeholder=" 按下回车全局搜索"
            @input="handelInputQuery"
            @focus="handleFocus"
            @keyup.enter="fullSearch"
            class="outline-none border-transparent text-base bg-transparent w-full z-10 input-caret-color-blue-500"
          />
        </div>
        <div class="w-8">
          <svg v-if="query.length" @click="reset()" class="icon text-grey cursor-pointer">
            <use xlink:href="#icon-close-outline" />
          </svg>
        </div>
      </div>
      <div v-if="meta && !hidden && !loading" class="overflow-y-auto" v-closable="closeable">
        <div class="px-3 font-normal text-xs text-grey">
          <span>搜索到{{meta.nbHits}}个结果</span>
        </div>
        <search-results
          class="py-2 px-3 w-full text-black border-b border-offwhite hover:bg-offwhite cursor-pointer last:border-b-0"
          v-for="(post,index) in posts"
          :key="index"
          :post="post"
        ></search-results>
      </div>
    </div>
  </div>
</template>


<script>
import LoadingIcon from "./LoadingIcon";
import SearchResults from "./SearchResults";

import * as api from "../../api/SimpleSearch";

export default {
  components: {
    LoadingIcon,
    SearchResults
  },

  mounted() {
    EventHub.$on("resetSearch", () => this.reset());
  },

  watch: {
    query: function(newQuery, oldQuery) {
      if (!newQuery) {
        this.reset();
      }
      this.search();
    }
  },

  data() {
    return {
      loading: false,
      hidden: true,
      query: "",
      posts: [],
      meta: null,
      closeable: {
        exclude: ["simpleSearchInput"],
        handler: "hiddenSearchResult"
      }
    };
  },

  methods: {
    handelInputQuery: _.debounce(function(e) {
      this.query = e.target.value;
    }, 300),

    async search() {
      if (!this.query.trim()) {
        return;
      }

      // TODO:cancel last request by cancel token
      if (this.loading) {
        return;
      }

      this.start();

      try {
        const response = await api.search(this.query);
        this.handleResponse(response);
      } catch (error) {
        console.log(error);
      }

      this.finished();
    },

    handleResponse(response) {
      this.posts = response.data;
      this.meta = response.meta;
    },

    hiddenSearchResult() {
      this.hidden = true;
    },

    handleFocus() {
      this.hidden = false;
    },

    fullSearch() {
      let location = { path: "/search" };

      if (this.query.trim()) {
        location.query = { query: this.query };
      }

      this.$router.push(location);
    },

    /**
     * initialize search status
     */
    reset() {
      this.posts = [];
      this.meta = null;
      this.loading = false;
      this.emptyQuery();
    },

    /**
     * search start
     */
    start() {
      this.loading = true;
      this.posts = [];
    },

    /**
     * search finished
     * only set search loading status to false now
     */
    finished() {
      this.loading = false;
      this.hidden = false;
    },

    /**
     * empty query and input value if exists
     */
    emptyQuery() {
      this.query = "";
      if (this.$refs.simpleSearchInput) {
        this.$refs.simpleSearchInput.value = "";
      }
    }
  }
};
</script>

<style lang="scss">
</style>
