<template>
  <main-layout>
    <template v-slot:header>
      <div class="flex items-center justify-between w-full px-4">
        <svg class="icon text-2xl text-blue-500 cursor-pointer" @click="goBack">
          <use xlink:href="#icon-arrow-back-outline" />
        </svg>
        <h1>{{ post.title }}</h1>
        <div class="text-2xl cursor-pointer hover:text-blue-500">
          <svg class="icon">
            <use xlink:href="#icon-more-horizontal-outline" />
          </svg>
        </div>
      </div>
    </template>

    <template v-slot:content>
      <div
        class="text-grey mb-2 text-sm"
      >创建于{{ post.created_at_human }} · 更新于{{ post.updated_at_human }} · 阅读{{ post.visited_count }}次</div>
      <tags v-if="post.tags && post.tags.length" :tags="post.tags" class="mb-2"></tags>
      <div class="markdown" v-html="markedBody"></div>
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
  props: ["id"],

  components: {
    Toc
  },

  async beforeRouteEnter(to, from, next) {
    try {
      next(vm => {
        vm.get();
        vm.lastRouteName = from.name;
      });
    } catch (error) {
      return next(false);
    }
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
    async get() {
      try {
        const response = await api.get(this.id);
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
      if (this.lastRouteName) {
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
    background-color: transparent;
    border-radius: 4px !important;
    border: 1px solid black;
  }
}
</style>
