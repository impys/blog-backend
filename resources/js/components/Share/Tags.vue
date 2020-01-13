<template>
  <div class="flex flex-wrap text-xs justify-between last-row-justify-start">
    <div
      @click.prevent="handleClick(tag)"
      v-for="(tag,index) in tags"
      :key="index"
      class="mr-1 text-blue-500 hover:shadow"
    >#{{ tag.name }}</div>
    <!-- <div
      v-for="(tag,index) in tags"
      :key="index"
      class="rounded px-1 mr-1 my-1 text-pink-400 border border-pink-400"
    >{{ tag.name }}</div>-->
  </div>
</template>

<script>
export default {
  props: ["tags"],
  methods: {
    handleClick(tag) {
      this.$router.push({
        name: "posts",
        query: { tag_id: tag.id, tag_name: tag.name }
      });
    }
  }
};
</script>
