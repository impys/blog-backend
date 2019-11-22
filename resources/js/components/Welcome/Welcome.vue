<template>
  <div class="flex flex-row justify-between">
    <main-content :data="data"></main-content>
    <sidebar></sidebar>
  </div>
</template>

<script>
import MainContent from "./MainContent";
import Sidebar from "./Sidebar";
export default {
  components: {
    MainContent,
    Sidebar
  },

  props: ["data"]
};
</script>
