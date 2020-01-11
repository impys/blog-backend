<template>
  <div class="flex justify-between bg-white">
    <div
      class="my-4 cursor-pointer text-grey font-semibold text-base hover:text-pink-400"
      :class="[currentType == type.type ? 'type-active' : '']"
      v-for="(type,index) in types"
      :key="index"
      @click="changeType(type)"
    >{{type.label}}</div>
  </div>
</template>

<script>
export default {
  props: ["currentType"],

  data() {
    return {
      types: [
        {
          type: "post",
          label: "文章"
        },
        {
          type: "video",
          label: "视频"
        },
        {
          type: "user",
          label: "用户"
        }
      ]
    };
  },
  methods: {
    changeType(type) {
      EventHub.$emit("changeType", type.type);
    }
  }
};
</script>

<style lang="scss">
.type-active {
  color: var(--color-pink-400);
  border-bottom: 2px solid var(--color-pink-400);
}
</style>
