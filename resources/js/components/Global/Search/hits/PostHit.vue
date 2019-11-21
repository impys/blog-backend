<template>
  <div class="flex flex-col w-full">
    <div class="mt-2 pb-2 w-full text-gray-700">
      <a :href="'/posts/' + data.id" class="outline-none block text-lg mb-2" v-html="data.title"></a>
      <div class="text-sm mb-2 w-full text-gray-600" v-html="data.body"></div>
      <div class="flex text-xs" v-if="data.tags.length">
        <div class="mr-1">
          <i class="fas fa-tags"></i>
        </div>
        <a
          v-for="(tag,index) in data.tags"
          :href="'/tags/' + tag.id"
          :key="index"
          class="outline-none block text-gray-600 rounded bg-gray-100 px-1 mr-1"
        >{{ tag.name }}</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["data"]
};
</script>
