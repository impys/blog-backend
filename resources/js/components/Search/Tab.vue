<template>
  <div class="flex justify-between bg-white">
    <div
      class="my-4 cursor-pointer text-grey font-semibold text-base hover:text-pink"
      :class="[currentTab.type == tab.type && currentTab.filter == tab.filter ? 'tab-active' : '']"
      v-for="(tab,index) in tabs"
      :key="index"
      @click="changeTab(tab)"
    >{{tab.label}}</div>
  </div>
</template>

<script>
export default {
  props: ["currentTab"],

  data() {
    return {
      tabs: [
        {
          type: "post",
          filter: null,
          label: "热门"
        },
        {
          type: "post",
          filter: "live",
          label: "最新"
        },
        {
          type: "user",
          filter: null,
          label: "用户"
        },
        {
          type: "tag",
          filter: null,
          label: "标签"
        },
        {
          type: "file",
          filter: "audio",
          label: "音频"
        },
        {
          type: "file",
          filter: null,
          label: "图片"
        }
      ]
    };
  },
  methods: {
    changeTab(tab) {
      EventHub.$emit("changeTab", tab);
    }
  }
};
</script>

<style lang="scss">
.tab-active {
  color: var(--color-pink);
  border-bottom: 2px solid var(--color-pink);
}
</style>
