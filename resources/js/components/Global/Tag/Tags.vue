<template>
  <div class="flex flex-wrap justify-between customer__tags">
    <div
      v-for="(tag,index) in tags"
      :key="index"
      class="text-gray-700 rounded px-1 mr-1 mb-1 bg-gray-100"
    >{{ tag.name }}</div>
  </div>
</template>

<script>
export default {
  props: ["tags"]
};
</script>
