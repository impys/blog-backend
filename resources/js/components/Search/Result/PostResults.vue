<template>
  <div>
    <post-card :post="post" v-for="(post,index) in posts" :key="index">
      <template v-slot:title>
        <!-- {{post.title}} -->
        <span v-html="post.title"></span>
      </template>
      <template v-slot:body>
        <!-- {{post.body}} -->
        <span v-html="post.body"></span>
      </template>
    </post-card>
  </div>
</template>

<script>
export default {
  props: ["data"],

  data() {
    return {
      posts: this.data
    };
  }
};
</script>
