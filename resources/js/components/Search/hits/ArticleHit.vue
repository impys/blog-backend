<template>
  <div class="flex flex-col">
    <div class="relative my-1 p-2 bg-gray-200 rounded-lg w-full">
      <a :href="'/articles/' + data.slug" class="block w-full h-full absolute left-0 top-0 z-10"></a>
      <h2 class="text-base mb-1" v-html="data.title"></h2>
      <div class="text-gray-600 text-sm" v-html="data.body"></div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["data"]
};
</script>
