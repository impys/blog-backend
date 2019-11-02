<template>
  <div
    class="ml-4 min-w-xp-320 sm:w-full lg:w-1/4 w-full max-h-vh-96 bg-white custom__box-shadow rounded-lg flex flex-col"
    v-bind:class="[loaded ? 'h-auto' : 'h-10']"
  >
    <div class="flex h-10 flex-row items-center min-h-10">
      <transition name="slide-fade" mode="out-in">
        <i class="fa fa-search text-lg block mx-2 text-red-400" v-if="!loading" key="unloading"></i>
        <loading-icon key="loading" v-if="loading"></loading-icon>
      </transition>
      <input
        type="text"
        v-model="query"
        @keyup.enter="search"
        class="outline-none border-transparent text-base pr-4 bg-transparent h-6 w-full"
      />
      <i
        class="fas fa-times text-lg block mx-2 text-gray-400 cursor-pointer"
        v-if="loaded"
        @click="closeSearchResult"
      ></i>
    </div>
    <div class="overflow-scroll min-h-10" v-if="loaded">
      <div v-if="hasSearchResult">
        <div v-for="(block,index) in data" :key="index">
          <hit-block :block="block" v-if="block.data.data.length"></hit-block>
        </div>
      </div>
      <div v-if="!hasSearchResult && !failed">
        <no-result></no-result>
      </div>
      <div v-if="failed">
        <failed></failed>
      </div>
    </div>
  </div>
</template>


<script>
const SEARCH_API = "/search";
import LoadingIcon from "./LoadingIcon";
import HitBlock from "./HitBlock";
import NoResult from "./NoResult";
import Failed from "./Failed";

export default {
  components: {
    LoadingIcon,
    HitBlock,
    NoResult,
    Failed
  },
  data() {
    return {
      loading: false,
      loaded: false,
      failed: false,
      query: "",
      data: []
    };
  },

  watch: {
    query: function(newQuery, oldQuery) {
      if (!newQuery) {
        this.closeSearchResult();
      }
    }
  },

  computed: {
    hasSearchResult() {
      return this.data.some(entity => {
        return entity.data.data.length;
      });
    }
  },

  methods: {
    search() {
      if (!this.query.trim()) {
        return;
      }
      this.loading = true;
      axios
        .get(SEARCH_API, {
          params: {
            query: this.query.trim()
          }
        })
        .then(res => {
          this.showSearchResutl();
          this.data = res.data.data;
          console.log(res.data.data);
        })
        .catch(e => {
          this.searchFailed();
        });
    },
    closeSearchResult() {
      this.query = null;
      this.loading = false;
      this.loaded = false;
    },
    searchFailed() {
      this.loading = false;
      this.loaded = true;
      this.failed = true;
    },
    showSearchResutl() {
      this.loading = false;
      this.loaded = true;
    }
  }
};
</script>

<style lang="scss">
.custom__search-input {
  transition: height 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 1.2s cubic-bezier(1, 0.5, 0.8, 1);
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}

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
  color: black;
  font-weight: 800;
}

.custom__box-shadow {
  box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14),
    0 3px 1px -2px rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);
}
</style>
