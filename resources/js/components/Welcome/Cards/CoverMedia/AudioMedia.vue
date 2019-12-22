<template>
  <div class="bg-grey rounded-t">
    <audio class="w-full" controls controlslist="nodownload" oncontextmenu="return false">
      <source :src="media.url" />
    </audio>
  </div>
  <!-- TODO:频谱可视化 -->
</template>
<script>
export default {
  props: ["media"]
};
</script>

<style lang="scss">
audio::-webkit-media-controls-current-time-display,
audio::-webkit-media-controls-time-remaining-display,
audio::-webkit-media-controls-timeline,
audio::-webkit-media-controls-volume-control-container {
  display: none;
}
audio::-webkit-media-controls-enclosure {
  background-color: transparent;
}
audio::-webkit-media-controls-panel {
  padding-left: 8px;
}
</style>
