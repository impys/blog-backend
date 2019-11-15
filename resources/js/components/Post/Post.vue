<template>
  <div class="w-full md:w-1/2 lg:w-1/3 px-4 pb-8 inline-block">
    <div class="relative custom__border font-light cursor-pointer">
      <a :href="'/posts/' + post.id" target="_blank" class="block w-full h-full absolute left-0 top-0 z-10"></a>
      <div class="absolute right-0 text-red-700 top-0 mr-2 mt-1 rotate-45" v-if="post.is_top">
        <i class="fas fa-thumbtack"></i>
      </div>
      <div class="flex items-center justify-between p-4 w-full h-16">
        <h3 class="text-base">{{ post.title }}</h3>
      </div>

      <div
        class="flex items-center justify-between pb-4 px-4 text-grey-darker text-xs text-gray-600"
      >
        <div class="mr-2">{{ post.created_at_human }}</div>
        <!-- <div class="flex">
          <div class="mr-2">
            <i class="far fa-heart"></i>
            {{ post.visited_count }}
          </div>
          <div class="mr-2">
            <i class="far fa-eye"></i>
            {{ post.upvote_count }}
          </div>
        </div>-->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["post"],

  mounted() {},

  methods: {}
};
</script>
