<template>
  <div
    class="flex flex-col w-full max-h-vh-45 bg-offwhite rounded-12 lg:rounded-20 hover:shadow-focus transition-box-shadow-05"
  >
    <div class="flex flex-row items-center min-h-7">
      <!-- icon -->
      <transition name="slide-fade" mode="out-in">
        <svg class="icon text-2xl block mx-2 text-blue-500" v-if="!searchLoading" key="unloading">
          <use xlink:href="#icon-search-outline" />
        </svg>
        <loading-icon key="loading" v-if="searchLoading" style="margin:0 1px;"></loading-icon>
      </transition>
      <!-- icon -->

      <input
        type="text"
        ref="searchInput"
        v-model="query"
        @keyup.enter="search()"
        class="outline-none border-transparent bg-transparent h-4 w-full z-10 input-caret-color-blue-500"
      />
      <i
        class="fas fa-times text-lg block mx-2 text-grey cursor-pointer"
        v-if="query.length"
        @click="resetSearch()"
      ></i>
    </div>

    <div v-if="meta && !searchLoading" class="overflow-y-auto px-2 lg:px-34-px pb-2 lg:pb-8-px">
      <!-- tips -->
      <div class="text-xs text-grey">
        <span>搜索到{{meta.total}}个结果</span>
      </div>
      <!-- tips -->

      <!-- posts listing -->
      <search-results v-for="(post,index) in posts" :key="index" :post="post"></search-results>
      <!-- posts listing -->

      <!-- footer -->
      <div v-if="meta.total" class="text-sm text-grey w-full text-center mt-2">
        <button
          v-if="!noMoreResult"
          :disabled="loadMoreLoading"
          @click="loadMore()"
          class="border border-blue-500 text-blue-500 rounded px-2 py-1"
        >{{loadMoreLoading ? '正在加载' : '加载更多'}}</button>
        <span class="text-xs" v-if="noMoreResult">到底了(゜-゜)つ~</span>
      </div>
      <!-- footer -->
    </div>
  </div>
</template>


<script>
import LoadingIcon from "./LoadingIcon";
import SearchResults from "./SearchResults";

import * as api from "../../api/Search";

export default {
  components: {
    LoadingIcon,
    SearchResults
  },

  watch: {
    query: function(newQuery, oldQuery) {
      if (!newQuery) {
        this.resetSearch();
      }
    }
  },

  computed: {
    noMoreResult: function() {
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

  data() {
    return {
      loadMoreLoading: false,
      searchLoading: false,
      query: "",
      posts: [],
      meta: null
    };
  },

  methods: {
    async search() {
      if (!this.query.trim()) {
        return;
      }
      this.searchStart();
      await this.fetch();
      this.searchFinished();
    },

    async loadMore() {
      this.loadMoreStart();
      await this.fetch();
      this.loadMoreFinished();
    },

    async fetch() {
      try {
        const response = await api.search(this.query, this.currentPage);
        this.posts.push(...response.data);
        this.meta = response.meta;
      } catch (error) {
        console.log(error);
      }
    },

    /**
     * initialize search status
     */
    resetSearch() {
      this.emptyLastSearchResult();
      this.query = "";
      this.searchLoading = false;
    },

    /**
     * search start
     */
    searchStart() {
      this.searchLoading = true;
      this.emptyLastSearchResult();
    },

    /**
     * search finished
     * only set search loading status to false now
     */
    searchFinished() {
      this.searchLoading = false;
    },

    /**
     * load more start
     */
    loadMoreStart() {
      this.loadMoreLoading = true;
    },

    /**
     * load more finished
     * only set load morre loading status to false now
     */
    loadMoreFinished() {
      this.loadMoreLoading = false;
    },

    /**
     * empty the last search result
     * prepare for the next search action
     */
    emptyLastSearchResult() {
      this.posts = [];
      this.meta = null;
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
  border-bottom: 2px solid var(--color-pink-400);
}
</style>
