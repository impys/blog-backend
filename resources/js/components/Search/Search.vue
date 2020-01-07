<template>
  <main-layout>
    <template v-slot:header>
      <div class="w-full relative h-10">
        <div
          class="w-full absolute transition-margin-03 top-0"
          :class="[searchBoxFixedTop?'mt-0':'mt-40-percent']"
        >
          <div class="flex items-center w-full bg-white">
            <div class="flex flex-row items-center w-full h-10 border border-ching rounded-full">
              <!-- <div class="flex flex-row items-center w-full h-8 bg-offwhite rounded-full"> -->
              <div class="w-8">
                <transition name="slide-fade" mode="out-in">
                  <svg class="icon block mx-2 text-ching" v-if="!loading" key="unloading">
                    <use xlink:href="#icon-search-outline" />
                  </svg>

                  <loading-icon key="loading" v-if="loading"></loading-icon>
                </transition>
                <!-- <svg class="icon block mx-2 text-ching" key="unloading">
                  <use xlink:href="#icon-search-outline" />
                </svg>-->
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
                <svg v-if="query" @click="reset()" class="icon text-grey cursor-pointer">
                  <use xlink:href="#icon-close-outline" />
                </svg>
              </div>
            </div>
            <div
              class="w-10 text-base text-ching text-right font-normal cursor-pointer"
              @click="goBack"
            >取消</div>
          </div>
          <tab class="sticky top-12" :currentTab="currentTab"></tab>
        </div>
      </div>
    </template>
    <template v-slot:content>
      <component class="mt-12" :is="currentTab.type+'-results'" v-if="data.length" :data="data"></component>
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
import * as api from "../../api/Search";
import PostResults from "./Result/PostResults";
import LoadingIcon from "./LoadingIcon";
import Tab from "./Tab";

export default {
  components: {
    PostResults,
    LoadingIcon,
    Tab
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
      searchBoxFixedTop: false,
      loading: false,
      data: [],
      meta: null,
      currentTab: {
        type: "post",
        filter: null
      },
      query: this.$route.query.query || ""
    };
  },

  async beforeRouteEnter(to, from, next) {
    next(vm => (vm.query = to.query.query || ""));
  },

  mounted() {
    EventHub.$on("changeTab", tab => {
      this.changeTab(tab);
    });
    this.search();
  },

  watch: {
    query(newValue, oldValue) {
      if (!newValue) return;

      let newQuery = {
        query: newValue
      };

      this.replaceRouteByNewQuery(newQuery);
      this.search();
    },

    currentTab(newValue, oldValue) {
      let newQuery = {
        type: newValue.type,
        filter: newValue.filter
      };

      this.replaceRouteByNewQuery(newQuery);
      this.search();
    }
  },

  methods: {
    async search() {
      if (this.prevent()) {
        return;
      }
      this.start();

      try {
        const response = await api.search(
          this.query,
          this.currentPage,
          this.currentTab.type,
          this.currentTab.filter
        );

        this.handleResponse(response);
      } catch (error) {
        console.log(error);
      }

      this.finished();
    },

    handleResponse(response) {
      this.searchBoxGoToTop();
      setTimeout(() => {
        this.data = response.data;
        this.meta = response.meta;
      }, 300);
    },

    prevent() {
      return this.loading || !this.query.trim();
    },

    changeTab(tab) {
      this.currentTab = tab;
    },

    start() {
      this.loading = true;
      this.emptyLastResults();
    },

    finished() {
      this.loading = false;
    },

    searchBoxGoToTop() {
      this.searchBoxFixedTop = true;
    },

    reset() {
      this.query = "";
      this.loading = false;
      this.emptyLastResults();
    },

    emptyLastResults() {
      this.data = [];
      this.meta = null;
    },

    getConcatedQuery(newQuery) {
      return _.pickBy(_.assign({}, this.$route.query, newQuery), OoO => OoO);
    },

    replaceRouteByNewQuery(newQuery) {
      let query = this.getConcatedQuery(newQuery);

      if (Object.keys(query).length === 0) {
        return;
      }
      this.$router.replace({ query: query });
    },

    goBack() {
      this.$router.go(-1);
    }
  }
};
</script>
