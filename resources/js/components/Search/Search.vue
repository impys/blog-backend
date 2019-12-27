<template>
  <div
    class="flex flex-col max-h-vh-96 bg-offwhite rounded"
    @mouseenter="handleMouseEnter()"
    @mouseleave="handleMouseLeave()"
  >
    <div class="flex flex-row items-center min-h-10">
      <!-- icon -->
      <transition name="slide-fade" mode="out-in">
        <i
          class="fa fa-search text-lg block mx-2 text-ching"
          v-if="!searchStatus.loading"
          key="unloading"
        ></i>
        <loading-icon key="loading" v-if="searchStatus.loading"></loading-icon>
      </transition>
      <!-- icon -->

      <input
        type="text"
        ref="searchInput"
        v-model="query"
        @keyup.enter="search"
        class="outline-none border-transparent bg-transparent h-6 w-full z-10 custom_search-input"
      />
      <i
        class="fas fa-times text-lg block mx-2 text-grey cursor-pointer"
        v-if="query.length"
        @click="resetSearch()"
      ></i>
    </div>

    <div v-if="searchStatus.loaded && !searchStatus.hidden" class="overflow-y-auto">
      <div class="text-xs text-grey" style="margin:0 34px 8px 34px" v-if="!searchStatus.loading">
        <span>搜索到{{meta ? meta.total : 0}}个结果</span>
        <span v-if="meta && meta.total">，用时{{meta.processingTimeMS/1000}}秒</span>
      </div>
      <div v-if="meta && meta.total" class="flex flex-wrap mb-2" style="margin:0 34px 8px 34px">
        <!-- posts listing -->
        <post-search-results
          class="border-b-2 border-white"
          v-for="(post,index) in posts"
          :key="index"
          :post="post"
        ></post-search-results>
        <!-- posts listing -->

        <!-- load more button -->
        <div
          class="text-sm text-grey my-3 w-full text-center cursor-pointer hover:text-ching"
          v-if="!noMoreResult"
          key="unloading"
          @click="loadMore()"
        >{{loadMoreStatus.loading ? '加载中...' : '加载更多'}}</div>

        <!-- load more button -->

        <div class="text-sm text-grey w-full text-center my-3" v-if="noMoreResult">到底了(゜-゜)つ~</div>
      </div>

      <div class="flex justify-center mb-2" v-if="searchStatus.failed">
        <div class="text-sm text-grey w-full text-center my-3">
          网络开小差了，
          <span class="text-ching" @click="search()">重试一下</span>吧~~
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import LoadingIcon from "./LoadingIcon";
import PostSearchResults from "./PostSearchResults";

import * as api from "../../api/search";

export default {
  components: {
    LoadingIcon,
    PostSearchResults
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
      searchStatus: {
        loading: false,
        loaded: false,
        failed: false,
        hidden: true
      },
      loadMoreStatus: {
        loading: false,
        failed: false
      },
      query: "",
      posts: [],
      meta: null
    };
  },

  methods: {
    async search() {
      if (this.preventSearch()) {
        return;
      }
      this.searchStart();
      try {
        let response = await api.search(this.query, this.currentPage);
        this.posts = response.data;
        this.meta = response.meta;
        this.searchSuccess();
      } catch (error) {
        this.searchFailed();
      }
    },
    async loadMore() {
      if (this.preventLoadMore()) {
        return;
      }
      this.loadMoreStart();
      try {
        let response = await api.search(this.query, this.currentPage);
        this.posts.push(...response.data);
        this.meta = response.meta;
        this.loadMoreFinished();
      } catch (error) {
        this.loadMoreFailed();
      }
    },

    // TODO:下面这几个函数逻辑写的有点复杂，应择一良辰，重构之
    resetSearch() {
      this.query = "";
      this.posts = [];
      this.searchStatus.loading = false;
      this.searchStatus.loaded = false;
    },
    searchStart() {
      this.searchStatus.loading = true;
      this.posts = [];
      this.meta = null;
    },
    searchSuccess() {
      this.searchStatus.loading = false;
      this.searchStatus.loaded = true;
      this.searchStatus.hidden = false;
    },
    searchFailed() {
      this.searchStatus.loading = false;
      this.searchStatus.loaded = true;
      this.searchStatus.failed = true;
    },
    preventSearch() {
      return !this.query.trim() || this.searchStatus.loading;
    },

    loadMoreStart() {
      this.loadMoreStatus.loading = true;
    },
    loadMoreFailed() {
      this.loadMoreStatus.loading = false;
    },
    searchFailed() {
      this.loadMoreStatus.failed = true;
    },
    preventLoadMore() {
      return this.loadMoreStatus.loading == true;
    },

    handleMouseEnter() {
      this.$refs.searchInput.focus();
      if (this.searchStatus.loaded) {
        this.searchStatus.hidden = false;
      }
    },
    handleMouseLeave() {
        this.searchStatus.hidden = true;
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
  color: var(--color-pink);
}

.custom__box-shadow {
  box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14),
    0 3px 1px -2px rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);
}

.custom_search-input {
  caret-color: var(--color-ching);
}
</style>
