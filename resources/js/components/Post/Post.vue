<template>
  <div class="w-full flex flex-row relative">
    <div class="w-1/4 hidden sm:hidden md:hidden lg:block"></div>
    <div
      class="w-full sm:w-full md:w-2/3 lg:w-1/2 pr-0 sm:pr-0 md:pr-4 lg:pr-4 pl-0 sm:pl-0 md:pl-0 lg:pl-4"
    >
      <div class="mb-10">
        <h1 class="text-4xl mb-2 ml-auto text-black">{{ post.title }}</h1>
        <div
          class="text-grey mb-2"
        >创建于{{ post.created_at_human }} · 更新于{{ post.updated_at_human }} · 阅读{{ post.visited_count }}次</div>
        <tags :tags="post.tags"></tags>
      </div>
      <div class="markdown-body" v-html="markedBody"></div>
    </div>
    <div class="w-1/4 hidden sm:hidden md:block lg:block">
      <toc :tocs="tocs" v-if="tocs.length" class="sticky" style="top:70px"></toc>
    </div>
  </div>
</template>

<script>
import marked from "marked";
import Toc from "./Toc";

export default {
  props: ["post"],

  components: {
    Toc
  },

  mounted() {
    this.setMarkedRendererAndToc();
  },

  computed: {
    markedBody: function() {
      return marked(this.post.body, { renderer: this.markedRenderer });
    }
  },

  data() {
    return {
      markedRenderer: null,
      tocs: []
    };
  },

  methods: {
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
    }
  }
};
</script>

<style lang="scss">
.markdown-body {
  audio {
    margin-bottom: 10px;
  }

  //   audio::-webkit-media-controls-current-time-display,
  //   audio::-webkit-media-controls-time-remaining-display,
  //   audio::-webkit-media-controls-timeline,
  audio::-webkit-media-controls-volume-control-container {
    display: none;
  }
  audio::-webkit-media-controls-enclosure {
    background-color: var(--color-offwhite);
    border-radius: 4px !important;
  }
}
</style>
