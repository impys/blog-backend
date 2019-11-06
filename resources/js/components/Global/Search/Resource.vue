<template>
  <div class="flex flex-wrap mb-2 pr-2 bg-white" style="margin:0 34px">
    <div class="mb-2 w-full flex justify-between sticky bg-white z-10" style="top:0">
      <div>
        <h2 class="text-base">{{ resource.label_zh }}</h2>
        <div class="bg-red-400 w-full h-1 rounded-lg shadow-md -mt-1"></div>
      </div>
    </div>
    <component
      :is="resource.label+'Hit'"
      v-for="(data,index) in resource.data"
      :key="index"
      :data="data"
    ></component>
  </div>
</template>

<script>
import PostHit from "./hits/PostHit";
import MusicHit from "./hits/MusicHit";

export default {
  components: {
    PostHit,
    MusicHit
  },
  props: ["resource"],
  mounted() {}
};
</script>
<style lang="scss">
</style>
