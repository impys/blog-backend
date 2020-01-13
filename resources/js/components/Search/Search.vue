<template>
  <main-layout>
    <template v-slot:header>
      <!-- search box -->
      <div class="flex items-center w-full bg-white px-4">
        <div class="flex flex-row items-center w-full h-8 bg-offwhite rounded-full">
          <div class="w-8">
            <transition name="slide-fade" mode="out-in">
              <svg class="icon block mx-2 text-blue-500" v-if="!searchLoading" key="unloading">
                <use xlink:href="#icon-search-outline" />
              </svg>

              <loading-icon key="loading" v-if="searchLoading"></loading-icon>
            </transition>
          </div>
          <div class="flex items-center w-full">
            <input
              v-focus
              v-model="keyword"
              @keyup.enter="debounceSearch"
              class="outline-none w-full border-transparent text-base bg-transparent w-full z-10 input-caret-color-blue-500"
            />
          </div>
          <div class="w-8">
            <svg v-if="keyword" @click="reset()" class="icon text-grey cursor-pointer">
              <use xlink:href="#icon-close-outline" />
            </svg>
          </div>
        </div>
        <div
          class="w-10 text-sm text-blue-500 text-right font-normal cursor-pointer"
          @click="goBack()"
        >取消</div>
      </div>
      <!-- search box -->
    </template>
    <template v-slot:content>
      <div class="py-1 px-4 -mx-4 sticky top-12 bg-white z-20" v-if="data.length">
        <ranking :rankingValue="ranking"></ranking>
      </div>
      <div class="text-sm text-grey my-4" v-if="!data.length && meta">什么也没搜到</div>
      <!-- search results -->
      <div v-scroll="handleScroll" v-if="data.length">
        <results :data="data" :meta="meta"></results>
        <div class="flex justify-center items-center text-sm text-grey my-4">
          <loading-icon key="loading" v-if="!isLastPage && loadMoreLoading"></loading-icon>
          <div v-if="isLastPage">到底了</div>
        </div>
      </div>
      <!-- search results -->
    </template>
    <template v-slot:sidebar-content>
      <div></div>
    </template>
    <template v-slot:sidebar-header>
      <div></div>
    </template>
  </main-layout>
</template>

<script>
import _ from "lodash";

import * as api from "../../api/Search";
import Results from "./Results";
import LoadingIcon from "./LoadingIcon";
import Ranking from "./Ranking";

export default {
  name: "search",

  props: ["ranking", "initialKeyword"],

  components: {
    Results,
    LoadingIcon,
    Ranking
  },

  watch: {
    ranking(newRanking, oldRanking) {
      this.search();
    }
  },

  computed: {
    isLastPage() {
      return helper.isLastPageByMeta(this.meta);
    },
    currentPage() {
      return helper.getCurrentByMeta(this.meta);
    },
    keyword: {
      get() {
        !this.initialKeyword && this.reset();
        return this.initialKeyword || "";
      },
      set(newKeyword) {
        this.$router.replace({ query: _.pickBy({ keyword: newKeyword }) });
        this.debounceSearch();
      }
    }
  },

  mounted() {
    this.search();
  },

  data() {
    return {
      //status flag
      searchLoading: false,
      loadMoreLoading: false,

      //search
      data: [],
      meta: null
    };
  },

  methods: {
    async fetch() {
      try {
        const response = await api.search(this.keyword, this.currentPage);
        this.handleResponse(response);
      } catch (error) {
        console.log(error);
      }
    },
    handleResponse(response) {
      this.data.push(...response.data);
      this.meta = response.meta;
    },

    //search
    debounceSearch: _.debounce(function(e) {
      this.search();
    }, 300),

    async search() {
      if (!this.keyword.trim()) {
        return;
      }

      this.emptySearchResult();

      this.searchLoading = true;

      await this.fetch();

      this.searchLoading = false;
    },

    // load more
    async loadMore() {
      if (this.loadMoreLoading) {
        return;
      }

      this.loadMoreLoading = true;

      await this.fetch();

      this.loadMoreLoading = false;
    },

    handleScroll() {
      return helper.handleScroll(this, "loadMore");
    },

    reset() {
      this.searchLoading = false;
      this.loadMoreLoading = false;
      this.emptySearchResult();
      this.$router.push({ name: "search" });
    },

    emptySearchResult() {
      this.data = [];
      this.meta = null;
    },

    goBack() {
      this.$router.go(-1);
    }
  }
};
</script>
