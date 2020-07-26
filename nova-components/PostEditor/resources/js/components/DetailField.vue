<template>
  <panel-item :field="field">
    <template slot="value">
      <button
        @click="showContent = !showContent"
        class="cursor-pointer dim inline-block text-primary font-bold"
      >Show Content</button>

      <div v-if="showContent" class="markdown-github mt-3" v-html="markedBody"></div>
    </template>
  </panel-item>
</template>

<script>
import { md } from "../md";

export default {
  props: ["resource", "resourceName", "resourceId", "field"],

  data() {
    return {
      showContent: false,
    };
  },

  computed: {
    markedBody: function () {
      if (this.field.value) {
        return md.render(this.field.value);
      }
    },
  },
};
</script>
