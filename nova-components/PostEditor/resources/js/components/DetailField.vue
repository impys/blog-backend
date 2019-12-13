<template>
  <panel-item :field="field">
    <template slot="value">
      <button
        class="outline-none font-bold text-primary mb-4"
        @click="toggleShowBody"
      >{{ showBody?'收起':'展开' }}</button>
      <div class="custom__markdown" v-html="field.value_marked" v-if="showBody"></div>
    </template>
  </panel-item>
</template>

<script>
export default {
  props: ["resource", "resourceName", "resourceId", "field"],
  data() {
    return {
      showBody: false
    };
  },
  mounted() {},

  methods: {
    toggleShowBody() {
      this.showBody = !this.showBody;
    }
  }
};
</script>
