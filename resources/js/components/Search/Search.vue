<template>
  <main-layout>
    <template v-slot:header>
      <!-- search box -->
      <div class="flex items-center w-full bg-white">
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
          @click="cancel"
        >取消</div>
      </div>
      <!-- search box -->
    </template>
    <template v-slot:content>
      <div class="py-1 sticky top-12 bg-white z-20" v-if="data.length">
        <ranking :initialRankingValue="currentRanking"></ranking>
      </div>
      <div class="text-sm text-grey my-4" v-if="!data.length && meta">什么也没搜到</div>
      <!-- search results -->
      <div v-scroll="handleScroll" v-if="data.length">
        <component :is="currentType+'-results'" :data="data" :meta="meta"></component>
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
import PostResults from "./Result/Post/PostResults";
import LoadingIcon from "./LoadingIcon";
import Ranking from "./Ranking";

export default {
  name: "search",
  components: {
    PostResults,
    LoadingIcon,
    Ranking
  },

  //   beforeRouteEnter(to, from, next) {
  //     console.log(to, from);
  //     // next();
  //     // next(vm => (vm.query = to.query.query || ""));
  //     next(vm => {
  //     //   vm.replaceRouteByCurrentKeyword();
  //     });
  //   },

  //   beforeRouteUpdate(to, from, next) {
  //     console.log(to, from);
  //     to.meta.keepAlive = true;
  //     next();
  //   },

  //   beforeRouteLeave(to, from, next) {
  //     console.log(to, from);
  //     if (to.name == "post") {
  //       from.meta.keepAlive = true;
  //     }
  //     next();
  //   },

  mounted() {
    EventHub.$on(
      "rankingChanged",
      rankingValue => (this.currentRanking = rankingValue)
    );
    // EventHub.$on("tagChanged", tag => (this.currentTag = tag));
  },

  watch: {
    keyword(newKeyword, oldKeyword) {
      this.replaceRouteByKeyword(newKeyword);
      if (!newKeyword.trim()) {
        this.reset();
      }
      this.debounceSearch();
    },

    currentRanking(newRanking, oldRanking) {
      this.replaceRouteByRanking(newRanking);
      this.debounceSearch();
    }
  },

  computed: {
    isLastPage: function() {
      return helper.isLastPageByMeta(this.meta);
    },
    currentPage: function() {
      return helper.getCurrentByMeta(this.meta);
    }
  },

  data() {
    return {
      //status flag
      searchLoading: false,
      loadMoreLoading: false,

      //search
      data: [],
      meta: null,
      keyword: null,

      //ranking and filter
      currentType: "post", // only support search post now
      currentRanking: null
    };
  },

  methods: {
    async fetch() {
      try {
        const response = await api.search(
          this.keyword,
          this.currentPage,
          this.currentRanking
        );
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
      if (this.preventSearch()) {
        return;
      }
      this.searchStart();

      await this.fetch();

      this.searchFinished();
    },
    preventSearch() {
      return !this.keyword.trim();
    },
    searchStart() {
      this.emptySearchResult();
      this.searchLoading = true;
    },
    searchFinished() {
      this.searchLoading = false;
    },

    // load more
    async loadMore() {
      if (this.preventLoadMore()) {
        return;
      }
      this.loadMoreStart();
      await this.fetch();
      this.loadMoreFinished();
    },
    preventLoadMore() {
      return this.loadMoreLoading;
    },
    loadMoreStart() {
      this.loadMoreLoading = true;
    },
    loadMoreFinished() {
      this.loadMoreLoading = false;
    },
    handleScroll() {
      return helper.handleScroll(this, "loadMore");
    },

    reset() {
      this.keyword = "";
      this.loading = false;
      this.currentRanking = null;
      this.emptySearchResult();
    },
    emptySearchResult() {
      this.data = [];
      this.meta = null;
    },

    //router query
    replaceRouteByNewQuery(newQuery) {
      let query = this.getConcatedQuery(newQuery);

      //   let query = _.assign({}, this.$route.query, newQuery);

      //   if (Object.keys(query).length === 0) {
      //     return;
      //   }
      this.$router.replace({ query: query });
    },
    getConcatedQuery(newQuery) {
      return _.pickBy(_.assign({}, this.$route.query, newQuery), OoO => OoO);
    },
    replaceRouteByKeyword(newKeyword) {
      this.replaceRouteByNewQuery({ keyword: newKeyword });
    },
    replaceRouteByRanking(newRanking) {
      this.replaceRouteByNewQuery({ ranking: newRanking });
    },

    cancel() {
      this.reset();
      this.goBack();
    },

    goBack() {
      this.$router.go(-1);
    }
  }
};
</script>
