<template>
  <div class="markdown-body" v-html="markedBody"></div>
</template>

<script>
import marked from "marked";

export default {
  props: ["body"],
  computed: {
    markedBody: function() {
      return marked(this.body);
    }
  }
};
</script>
