<template>
  <main-layout>
    <template v-slot:header>
      <h1>标签</h1>
    </template>
    <template v-slot:content>
      <div class="mt-4">
        <router-link
          class="block text-blue-500 text-lg mb-2"
          v-for="(tag,index) in TagsWithPost"
          :key="index"
          :to="{ name: 'posts', query : { tag_id: tag.id, tag_name:tag.name } }"
        >
          <span>#{{tag.name}}({{tag.posts_count}})</span>
        </router-link>
      </div>
    </template>
  </main-layout>
</template>



<script>
import * as api from "../../api/GetTags";

export default {
  name: "tags",

  computed: {
    TagsWithPost() {
      return this.tags.filter(tag => tag.posts_count);
    }
  },

  mounted() {
    this.get();
  },

  data() {
    return {
      tags: []
    };
  },

  methods: {
    async get() {
      try {
        const response = await api.get();
        this.handleResponse(response);
      } catch (error) {
        return next(false);
      }
    },
    handleResponse(response) {
      this.tags = response.data;
    }
  }
};
</script>
