<template>
  <div class="flex flex-wrap px-4">
    <div v-for="n in lastPage" :key="n" class="pr-1">
      <div
        v-if="n == currentPage"
        class="flex items-center justify-center border-gray-700 relative border rounded-full mb-2 w-8 h-8"
        v-bind:style="{ color: color ,borderColor:color}"
      >
        <a :href="path + '?page=' + n" class="block w-full h-full absolute left-0 top-0 z-10"></a>
        {{ n }}
      </div>

      <div
        v-if="n != currentPage"
        class="flex items-center justify-center border-gray-700 relative border rounded-full mb-2 w-8 h-8"
      >
        <a :href="path + '?page=' + n" class="block w-full h-full absolute left-0 top-0 z-10"></a>
        {{ n }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["currentPage", "lastPage", "path", "color"],
  mounted() {},
  data() {
    return {};
  }
};
</script>
