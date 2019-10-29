<template>
  <div>
    <div class="flex flex-wrap mb-2 px-4 sticky top-0 bg-white z-10">
      <div class="md:w-1/2 mb-2 lg:w-1/3 flex justify-between">
        <div>
          <h2 class="text-2xl">
            <a href="/articles">{{ block.label }}</a>
          </h2>
          <div
            class="bg-red-400 w-full h-2 rounded-lg shadow-md -mt-2"
            :style="{backgroundColor:block.color}"
          ></div>
        </div>
      </div>
    </div>

    <div class="flex flex-wrap">
      <article-card
        :article="article"
        :color="block.color"
        v-for="(article,index) in block.articles"
        :key="index"
      ></article-card>
    </div>
  </div>
</template>

<script>
import ArticleCard from "./ArticleCard";
export default {
  components: {
    ArticleCard
  },

  props: ["block"]
};
</script>
