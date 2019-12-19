<template>
  <panel-item :field="field">
    <template slot="value">
      <button
        class="outline-none font-bold text-primary mb-4"
        @click="toggleShowBody"
      >{{ showBody?'收起':'展开' }}</button>
      <div class="custom__markdown" v-html="markedBody" v-if="showBody"></div>
    </template>
  </panel-item>
</template>

<script>
import marked from "marked";

export default {
  props: ["resource", "resourceName", "resourceId", "field"],
  data() {
    return {
      showBody: false
    };
  },

  updated() {
    Prism.highlightAll();
  },

  computed: {
    markedBody: function() {
      return marked(this.field.value);
    }
  },

  methods: {
    toggleShowBody() {
      this.showBody = !this.showBody;
    }
  }
};
</script>
