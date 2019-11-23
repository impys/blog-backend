<template>
  <div class="px-4 pb-8">
    <div class="relative custom__border font-light">
      <div class="absolute right-0 text-red-700 top-0 mr-2 mt-1 rotate-45" v-if="post.is_top">
        <i class="fas fa-thumbtack"></i>
      </div>

      <img :src="post.cover" height="20px" alt />

      <div class="flex items-center justify-between mx-4 my-2">
        <a :href="'/posts/' + post.id" class="outline-none block text-sm z-10">{{ post.title }}</a>
      </div>

      <div class="flex text-xs mx-4 my-2" v-if="post.tags.length">
        <div class="mr-1">
          <i class="fas fa-tags"></i>
        </div>
        <a
          v-for="(tag,index) in post.tags"
          :href="'/tags/' + tag.id"
          :key="index"
          class="outline-none block text-gray-600 rounded bg-gray-100 px-1 mr-1 z-10"
        >{{ tag.name }}</a>
      </div>

      <div class="flex items-center justify-between mx-4 my-2 text-xs text-gray-600">
        <div class="mr-2">{{ post.created_at_human }}</div>
        <div class="flex">
          <div class="mr-2">
            <i class="far fa-heart"></i>
            {{ post.visited_count }}
          </div>
          <div class="mr-2">
            <i class="far fa-eye"></i>
            {{ post.upvote_count }}
          </div>
        </div>
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
