<template>
  <div class="flex flex-col w-full">
    <div class="relative mt-2 pb-2 border-b border-gray-300 w-full text-gray-700">
      <a :href="'/posts/' + data.id" class="block w-full h-full absolute left-0 top-0 z-10"></a>
      <h2 class="text-base mb-1 w-full" v-html="data.title"></h2>
      <div class="text-sm w-full" v-html="data.body"></div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["data"]
};
</script>
