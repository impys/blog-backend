<template>
  <div class="w-full md:w-1/2 mb-6 lg:mb-6 px-4 lg:px-4 lg:w-1/3">
    <article class="relative custom__border font-light cursor-pointer">
      <a :href="'/articles/' + article.slug" class="block w-full h-full absolute left-0 top-0 z-10"></a>
      <div
        class="absolute right-0 top-0 mr-2 mt-1 rotate-45"
        v-if="article.is_top"
        :style="{ color:color }"
      >
        <i class="fas fa-thumbtack"></i>
      </div>
      <div class="flex items-center justify-between leading-tight p-4 block w-full h-16">
        <h3 class="text-base">{{ article.title }}</h3>
      </div>

      <div
        class="flex items-center justify-between leading-none pb-4 px-4 text-grey-darker text-xs"
      >
        <div class="mr-2">{{ article.created_at_human }}</div>
        <div class="flex">
          <div class="mr-2">
            <i class="far fa-heart"></i>
            {{ article.visited_count }}
          </div>
          <div class="mr-2">
            <i class="far fa-eye"></i>
            {{ article.upvote_count }}
          </div>
        </div>
      </div>
    </article>
  </div>
</template>

<script>
export default {
  props: ["article", "color"],

  mounted() {},

  methods: {}
};
</script>
