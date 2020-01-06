<template>
  <main-layout>
    <template v-slot:header>
      <svg class="icon text-2xl text-ching cursor-pointer" @click="goBack">
        <use xlink:href="#icon-arrow-back-outline" />
      </svg>
      <h1 class="text-base lg:text-xl text-black mx-auto">{{ post.title }}</h1>
      <div class="text-2xl cursor-pointer hover:text-ching">
        <svg class="icon">
          <use xlink:href="#icon-more-horizontal-outline" />
        </svg>
      </div>
    </template>
    <template v-slot:content>
      <div
        class="text-grey mb-2 text-sm"
      >创建于{{ post.created_at_human }} · 更新于{{ post.updated_at_human }} · 阅读{{ post.visited_count }}次</div>
      <tags v-if="post.tags && post.tags.length" :tags="post.tags" class="mb-2"></tags>
      <div class="markdown-body" v-html="markedBody"></div>
    </template>
    <template v-slot:sidebar-content>
      <toc :tocs="tocs" v-if="tocs.length" class="sticky top-12"></toc>
    </template>
  </main-layout>
</template>

<script>
import * as api from "../../api/GetPost";
import marked from "marked";
import Toc from "./Toc";

export default {
  components: {
    Toc
  },

  async beforeRouteEnter(to, from, next) {
    try {
      const response = await api.get(to.params.id);
      next(vm => {
        vm.handleResponse(response);
        vm.lastRouteName = from.name;
      });
    } catch (error) {
      return next(false);
    }
  },

  async beforeRouteUpdate(to, from, next) {
    this.get(to.params.id);
    next();
  },

  mounted() {
    this.setMarkedRendererAndToc();
  },

  watch: {
    "post.body": function(value) {
      this.$nextTick(() => Prism.highlightAll());
    }
  },

  computed: {
    markedBody: function() {
      if (this.post.body) {
        return marked(this.post.body, { renderer: this.markedRenderer });
      }
    }
  },

  data() {
    return {
      post: {},
      markedRenderer: {},
      tocs: [],
      lastRouteName: null
    };
  },

  methods: {
    async get(postId) {
      try {
        const response = await api.get(postId);
        this.handleResponse(response);
      } catch (error) {
        console.log(error);
      }
    },
    handleResponse(response) {
      this.post = response.data;
    },

    setMarkedRendererAndToc() {
      const renderer = new marked.Renderer();
      const tocs = [];
      renderer.heading = function(text, level) {
        // only need h2 and h3
        if (level > 1 && level < 4) {
          tocs.push({
            level: level,
            value: text
          });
        }

        return `<h${level} id="${text}" class="toc-anchor">${text}</h${level}>`;
      };

      this.tocs = tocs;

      this.markedRenderer = renderer;
    },

    goBack() {
      if (this.lastRouteName == "home") {
        this.$router.go(-1);
      } else {
        this.$router.push({ path: "/" });
      }
    }
  }
};
</script>

<style lang="scss">
.markdown-body {
  audio {
    margin-bottom: 10px;
  }

  audio::-webkit-media-controls-volume-control-container {
    display: none;
  }
  audio::-webkit-media-controls-enclosure {
    background-color: var(--color-offwhite);
    border-radius: 4px !important;
  }
}
</style>
