<template>
  <div class="w-full md:w-1/2 mb-6 lg:mb-6 px-4 lg:px-4 lg:w-1/3">
    <!-- <article class="overflow-hidden rounded text-white" :style="{backgroundColor:color}"> -->
    <!-- <article class="overflow-hidden rounded border-2" :style="{borderColor:color}"> -->
      <article class="overflow-hidden custom__border">
      <!-- <article class="overflow-hidden rounded border-2 border-gray-600"> -->
      <div class="flex items-center justify-between leading-tight p-4 block w-full h-16">
        <h3 class="text-base">文章标题</h3>
      </div>

      <div class="flex items-center justify-between leading-none pb-4 px-4">
        <p class="text-grey-darker text-sm">14/4/19</p>
      </div>
    </article>
  </div>
</template>

<script>
export default {
  props: ["articles", "color"]
};
</script>
