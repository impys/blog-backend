<template>
  <div class="flex flex-wrap px-4">
    <div v-for="n in lastPage" :key="n" :class="[ (n != lastPage) ? 'pr-1':'']">
      <div
        class="flex items-center justify-center border-gray-700 border rounded-full mb-2 w-8 h-8 cursor-pointer"
        v-bind:class="[ (n == currentPage) ? 'border-red-400':'',(n == currentPage) ? 'text-red-400':'']"
        v-on:click="handleCurrnetPage(n)"
      >{{ n }}</div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["lastPage"],
  computed: {},
  mounted() {},
  data() {
    return {
      currentPage: 1
    };
  },
  methods: {
    handleCurrnetPage(currentPage) {
      this.currentPage = currentPage;
      EventHub.$emit("updateCurrentPage", currentPage);
    }
  }
};
</script>
