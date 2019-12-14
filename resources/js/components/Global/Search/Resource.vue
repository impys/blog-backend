<template>
  <div class="flex flex-wrap mb-2 pb-2 bg-white" style="margin:0 34px">
    <div class="mb-2 w-full flex justify-between sticky bg-white z-10" style="top:0">
      <div>
        <h2 class="text-lg">{{ resource.label_zh }}</h2>
        <div class="bg-primary w-full h-1 rounded-lg shadow-md -mt-1"></div>
      </div>
    </div>
    <component
      :is="resource.label+'Hit'"
      v-for="(data,index) in resource.data"
      :key="index"
      :data="data"
      class="border-gray-300"
      :class="{'border-b':index != resource.data.length - 1}"
    ></component>
  </div>
</template>

<script>
import PostHit from "./hits/PostHit";

export default {
  components: {
    PostHit
  },
  props: ["resource"]
};
</script>
