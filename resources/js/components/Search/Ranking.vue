<template>
  <div class="flex">
    <div
      class="text-xs rounded p-1 mr-2 cursor-pointer hover:bg-blue-500 hover:text-white"
      :class="[!rankingValue ? 'bg-blue-500 text-white' : 'bg-white text-grey']"
      @click="handleClick(null)"
    >最新发布</div>
    <div
      v-for="(ranking,index) in rankings"
      :key="index"
      class="text-xs rounded p-1 mr-2 cursor-pointer hover:bg-blue-500 hover:text-white"
      :class="[rankingValue && rankingValue == ranking.value ? 'bg-blue-500 text-white' : 'bg-white text-grey']"
      @click="handleClick(ranking.value)"
    >{{ ranking.label }}</div>
  </div>
</template>
<script>
export default {
  props: ["rankingValue"],

  data() {
    return {
      rankings: [
        {
          value: "latest-update",
          label: "最近更新"
        },
        {
          value: "most-visited",
          label: "最多阅读"
        }
      ]
    };
  },

  methods: {
    handleClick(rankingValue) {
      this.$router.replace({
        query: _.pickBy({ ...this.$route.query, ranking: rankingValue })
      });
    }
  }
};
</script>
