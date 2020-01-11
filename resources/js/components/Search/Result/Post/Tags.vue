<template>
  <div class="flex py-2">
    <div
      v-for="(tag,index) in tags"
      :key="index"
      class="text-xs rounded p-1 mr-2 cursor-pointer hover:bg-blue-500 hover:text-white"
      :class="[currentTag.value == tag.value ? 'bg-blue-500 text-white' : 'bg-white text-grey']"
      @click="handleClick(tag)"
    >{{ tag.label }}</div>
  </div>
</template>
<script>
export default {
  props: ["initialTags"],
  data() {
    return {
      currentTag: {
        value: "default",
        label: "全部标签"
      },
      tags: [
        {
          value: "default",
          label: "全部标签"
        }
      ]
    };
  },

  mounted() {
    this.setTags();
  },

  methods: {
    setTags() {
      this.tags.push(...this.initialTags);
    },
    handleClick(tag) {
      this.currentTag = tag;
      EventHub.$emit("tagChanged", tag);
    }
  }
};
</script>
