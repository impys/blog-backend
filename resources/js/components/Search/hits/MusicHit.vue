<template>
  <div class="flex flex-row">
    <div
      @click="clickMusic"
      class="mb-2 p-1 ml-2 bg-gray-200 text-xs rounded-lg cursor-pointer"
    >{{ data.title }}-{{ data.artist }}</div>
  </div>
</template>

<script>
export default {
  props: ["data"],
  methods: {
    clickMusic() {
      EventHub.$emit("clickMusic", this.data);
    }
  }
};
</script>
