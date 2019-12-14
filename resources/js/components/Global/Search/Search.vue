<template>
  <div
    class="w-full sm:w-full lg:w-1/3 max-h-vh-96 bg-white custom__box-shadow rounded-lg flex flex-col"
    v-bind:class="[loaded && !hidden ? 'h-auto' : 'h-10']"
    @mouseenter="handleMouseEnter()"
    @mouseleave="handleMouseLeave()"
  >
    <div class="flex h-10 flex-row items-center min-h-10">
      <transition name="slide-fade" mode="out-in">
        <i class="fa fa-search text-lg block mx-2 text-primary" v-if="!loading" key="unloading"></i>
        <loading-icon key="loading" v-if="loading"></loading-icon>
      </transition>
      <input
        type="text"
        ref="searchInput"
        v-model="query"
        @keyup.enter="search"
        class="outline-none border-transparent text-sm bg-transparent h-6 w-full z-10 custpm_search-input"
      />
      <i
        class="fas fa-times text-lg block mx-2 text-gray-400 cursor-pointer"
        v-if="query.length"
        @click="resetSearchInput()"
      ></i>
    </div>

    <div v-if="loaded && !hidden" class="overflow-y-auto">
      <div v-for="(resource,index) in data" :key="index">
        <resource :resource="resource" v-if="resource.data.length"></resource>
      </div>
      <div class="flex justify-center mb-2">
        <i class="far fa-frown text-lg block" key="noresult" v-if="!hasSearchResult || failed"></i>
      </div>
    </div>
  </div>
</template>


<script>
const SEARCH_API = "/search";
import LoadingIcon from "./LoadingIcon";
import Resource from "./Resource";

export default {
  components: {
    LoadingIcon,
    Resource
  },
  data() {
    return {
      loading: false,
      loaded: false,
      failed: false,
      hidden: true,
      query: "",
      data: []
    };
  },

  watch: {
    query: function(newQuery, oldQuery) {
      if (!newQuery) {
        this.resetSearchInput();
      }
    }
  },

  computed: {
    hasSearchResult() {
      return this.data.some(item => {
        return item.data.length;
      });
    }
  },

  methods: {
    search() {
      if (!this.query.trim()) {
        return;
      }
      if (this.loading == true) {
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
          this.searchSuccess();
          this.data = res.data;
        })
        .catch(e => {
          this.searchFailed();
        });
    },
    resetSearchInput() {
      this.query = "";
      this.loading = false;
      this.loaded = false;
    },
    searchFailed() {
      this.loading = false;
      this.loaded = true;
      this.failed = true;
    },
    searchSuccess() {
      this.loading = false;
      this.loaded = true;
      this.hidden = false;
    },
    handleMouseEnter() {
      this.$refs.searchInput.focus();
      if (this.loaded) {
        this.hidden = false;
      }
    },
    handleMouseLeave() {
      this.hidden = true;
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
  color: var(--color-primary);
}

.custom__box-shadow {
  box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14),
    0 3px 1px -2px rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);
}

.custpm_search-input {
  caret-color: var(--color-primary);
}
</style>
