<template>
  <div class="flex flex-col w-full max-h-vh-45 border border-ching rounded bg-white">
    <div class="flex flex-row items-center min-h-7">
      <div class="w-8">
        <transition name="slide-fade" mode="out-in">
          <svg class="icon block mx-2 text-ching" v-if="!searchLoading" key="unloading">
            <use xlink:href="#icon-search-outline" />
          </svg>

          <loading-icon key="loading" v-if="searchLoading"></loading-icon>
        </transition>
      </div>
      <div class="flex items-center w-full">
        <input
          type="text"
          ref="searchInput"
          placeholder=" ↵ 全局搜索"
          @input="handelInputQuery"
          @focus="handleFocus"
          @keyup.enter="fullSearch"
          class="outline-none border-transparent text-base bg-transparent w-full z-10 input-caret-color-ching"
        />
      </div>
      <div class="w-8">
        <svg v-if="query.length" @click="reset()" class="icon text-grey cursor-pointer">
          <use xlink:href="#icon-close-outline" />
        </svg>
      </div>
    </div>
    <div v-if="meta && !hidden && !searchLoading" class="overflow-y-auto" v-closable="closeable">
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
      searchLoading: false,
      hidden: true,
      query: "",
      posts: [],
      meta: null,
      closeable: {
        exclude: ["searchInput"],
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
      if (this.searchLoading) {
        return;
      }

      this.start();

      try {
        const response = await api.search(this.query);
        this.posts = response.data;
        this.meta = response.meta;
      } catch (error) {
        console.log(error);
      }

      this.finished();
    },

    hiddenSearchResult() {
      this.hidden = true;
    },

    handleFocus() {
      this.hidden = false;
    },

    fullSearch() {
      this.$router.push({ path: "/search", query: { query: this.query } });
    },

    /**
     * initialize search status
     */
    reset() {
      this.posts = [];
      this.meta = null;
      this.searchLoading = false;
      this.emptyQuery();
    },

    /**
     * search start
     */
    start() {
      this.searchLoading = true;
      this.posts = [];
    },

    /**
     * search finished
     * only set search loading status to false now
     */
    finished() {
      this.searchLoading = false;
      this.hidden = false;
    },

    /**
     * empty query and input value if exists
     */
    emptyQuery() {
      this.query = "";
      if (this.$refs.searchInput) {
        this.$refs.searchInput.value = "";
      }
    }
  }
};
</script>

<style lang="scss">
.slide-fade-enter-active {
  transition: all 0.2s;
}
.slide-fade-leave-active {
  transition: all 0.2s;
}
.slide-fade-enter {
  transform: translateX(-10px);
  opacity: 0;
}
.slide-fade-leave-to {
  transform: translateX(-10px);
  opacity: 0;
}

.highlight {
  border-bottom: 2px solid var(--color-pink);
}
</style>
