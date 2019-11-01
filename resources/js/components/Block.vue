<template>
  <div>
    <div class="flex flex-wrap mb-2 px-4 sticky bg-white z-10" style="top:4rem">
      <div class="md:w-1/2 mb-2 lg:w-1/3 flex justify-between">
        <div>
          <h2 class="text-2xl">
            <a :href="'/' + block.url">{{ block.label }}</a>
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
        v-for="(article,index) in block.data.data"
        :key="index"
      ></article-card>
    </div>

    <pagination
      v-if="block.data.first_page_url"
      :currentPage="block.data.current_page"
      :lastPage="block.data.last_page"
      :path="block.data.path"
      :color="block.color"
    ></pagination>
  </div>
</template>

<script>
import ArticleCard from "./ArticleCard";
import Pagination from "./Pagination";
export default {
  components: {
    ArticleCard,
    Pagination
  },

  props: ["block"],

  mounted() {
    console.log(this.block);
  }
};
</script>
