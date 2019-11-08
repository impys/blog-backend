<template>
  <panel-item :field="field">
    <template slot="value">
      <div class="custom__markdown" v-html="field.value_marked"></div>
    </template>
  </panel-item>
</template>

<script>
export default {
  props: ["resource", "resourceName", "resourceId", "field"],
  mounted() {}
};
</script>
