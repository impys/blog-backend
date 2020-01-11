<template>
  <div class="flex flex-wrap text-xs justify-between last-row-justify-start">
    <div
      v-for="(tag,index) in tags"
      :key="index"
      class="rounded px-1 mr-1 my-1 text-pink-400 border border-pink-400"
    >{{ tag.name }}</div>
  </div>
</template>

<script>
export default {
  props: ["tags"]
};
</script>
