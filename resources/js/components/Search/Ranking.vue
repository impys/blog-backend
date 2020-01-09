<template>
  <div class="flex py-2">
    <div
      class="text-xs rounded p-1 mr-2 cursor-pointer hover:bg-blue-500 hover:text-white"
      :class="[!initialRankingValue ? 'bg-blue-500 text-white' : 'bg-white text-grey']"
      @click="handleClick(null)"
    >最新发布</div>
    <div
      v-for="(ranking,index) in rankings"
      :key="index"
      class="text-xs rounded p-1 mr-2 cursor-pointer hover:bg-blue-500 hover:text-white"
      :class="[initialRankingValue && initialRankingValue == ranking.value ? 'bg-blue-500 text-white' : 'bg-white text-grey']"
      @click="handleClick(ranking.value)"
    >{{ ranking.label }}</div>
  </div>
</template>
<script>
export default {
  props: ["initialRankingValue"],

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
      EventHub.$emit("rankingChanged", rankingValue);
    }
  }
};
</script>
