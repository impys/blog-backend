<template>
  <div class="lg:w-1/3 hidden lg:block ml-4">
    <tags :tags="tags" class="mb-2"></tags>
    <music class="mb-2"></music>
  </div>
</template>


<script>
import Tags from "./Tags";
export default {
  components: {
    Tags
  },

  props: ["tags"]
};
</script>
