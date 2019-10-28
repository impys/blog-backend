<template>
  <div>
    <div class="mb-12" v-for="(block,index) in blocks" :key="index">
      <div class="flex mb-4 flex-wrap -mx-1 lg:-mx-4 sticky top-0 bg-white z-10">
        <div class="md:w-1/2 my-2 px-4 lg:px-4 lg:w-1/3 flex justify-between">
          <div>
            <h2 class="text-2xl">
              <a href>{{ block.label }}</a>
            </h2>
            <div
              class="bg-red-400 w-full h-2 rounded-lg shadow-md -mt-2"
              :style="{backgroundColor:block.color}"
            ></div>
          </div>
        </div>
      </div>

      <div class="flex flex-wrap -mx-1 lg:-mx-4">
        <article-card
          :article="article"
          :color="block.color"
          v-for="(article,index) in articles"
          :key="index"
        ></article-card>
      </div>
    </div>
  </div>
</template>

<script>
import ArticleCard from "./ArticleCard";

export default {
  components: {
    ArticleCard
  },

  props: ["blocks"],

  data() {
    return {
      articles: [
        { title: "测试" },
        { title: "测试" },
        { title: "测试" },
        { title: "测试" },
        { title: "测试" },
        { title: "测试" }
      ]
    };
  }
};
</script>
