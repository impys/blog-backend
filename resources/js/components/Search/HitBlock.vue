<template>
  <div class="flex flex-wrap mb-2 pr-2 bg-white" style="margin-left:34px">
    <div class="mb-2 w-full flex justify-between sticky bg-white z-10" style="top:0">
      <div>
        <h2 class="text-base">
          <a :href="'/' + block.url">{{ block.label }}</a>
        </h2>
        <div
          class="bg-red-400 w-full h-1 rounded-lg shadow-md"
          :style="{backgroundColor:block.color}"
        ></div>
      </div>
    </div>
    <div class="flex flex-col">
      <component
        :is="block.resource+'Hit'"
        v-for="(data,index) in block.data.data"
        :key="index"
        :data="data"
      ></component>
    </div>
  </div>
</template>

<script>
import ArticleHit from "./hits/ArticleHit";
import VideoHit from "./hits/VideoHit";
export default {
  components: {
    ArticleHit,
    VideoHit
  },
  props: ["block"],
  mounted() {
    console.log(this.block);
  }
};
</script>
<style lang="scss">
</style>
