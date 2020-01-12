<template>
  <main-layout>
    <template v-slot:header>
      <h1>标签</h1>
    </template>
    <template v-slot:content>
      <router-link
        v-for="(tag,index) in TagsWithPost"
        :key="index"
        :to="{ name: 'posts', query : { tag_id: tag.id, tag_name:tag.name} }"
        class="flex mt-4 w-full lg:w-1/2 items-center hover:text-blue-500 text-lg mb-2"
      >
        <div>
          <span>{{tag.name}}</span>
          <span class="bg-blue-500 px-1 rounded-full text-sm text-white">{{tag.posts_count}}</span>
        </div>
        <svg class="icon">
          <use xlink:href="#icon-arrow-ios-forward-outline" />
        </svg>
      </router-link>
    </template>
  </main-layout>
</template>



<script>
import * as api from "../../api/GetTags";

export default {
  name: "tags",

  async beforeRouteEnter(to, from, next) {
    try {
      const response = await api.get();
      next(vm => {
        vm.handleResponse(response);
      });
    } catch (error) {
      return next(false);
    }
  },

  computed: {
    TagsWithPost() {
      return this.tags.filter(tag => tag.posts_count);
    }
  },

  data() {
    return {
      tags: []
    };
  },

  methods: {
    handleResponse(response) {
      this.tags = response.data;
    }
  }
};
</script>
