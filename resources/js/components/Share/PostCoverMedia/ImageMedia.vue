<template>
  <div
    class="h-56 w-full bg-cover bg-center rounded"
    :style="{'backgroundImage':'url('+media.url+')'}"
  ></div>
</template>
<script>
export default {
  props: ["media"]
};
</script>
