<template>
  <div class="flex flex-col w-full">
    <div class="relative mt-2 pb-2 w-full text-gray-700">
      <a
        :href="'/posts/' + data.id"
        target="_blank"
        class="absolute top-0 left-0 z-10 w-full h-full"
      ></a>
      <div class="text-sm mb-2" v-html="data.title"></div>
      <div class="text-xs mb-2 w-full" v-html="data.body"></div>
      <tags :tags="data.tags" v-if="data.tags.length"></tags>
    </div>
  </div>
</template>

<script>
export default {
  props: ["data"]
};
</script>
