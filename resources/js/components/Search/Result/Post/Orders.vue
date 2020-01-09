<template>
  <div class="flex py-2">
    <div
      v-for="(order,index) in orders"
      :key="index"
      class="text-xs rounded p-1 mr-2 cursor-pointer hover:bg-blue-500 hover:text-white"
      :class="[currentOrder.value == order.value ? 'bg-blue-500 text-white' : 'bg-white text-grey']"
      @click="handleClick(order)"
    >{{ order.label }}</div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      currentOrder: {
        value: "latest_created",
        label: "最新发布"
      },
      orders: [
        {
          value: "latest_created",
          label: "最新发布"
        },
        {
          value: "most_visited",
          label: "最多阅读"
        },
        {
          value: "latest_update",
          label: "最近更新"
        }
      ]
    };
  },

  methods: {
    handleClick(order) {
      this.currentOrder = order;
      EventHub.$emit("orderChanged", order);
    }
  }
};
</script>
