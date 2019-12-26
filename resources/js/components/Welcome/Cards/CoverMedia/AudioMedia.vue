<template>
  <div class="rounded-t relative">
    <audio
      :id="'audio-'+media.id"
      class="w-full z-10"
      crossorigin="anonymous"
      controls
      controlslist="nodownload"
      @play="handlePlay"
    >
      <source :src="media.url" />
    </audio>
    <canvas
      :id="'canvas-'+media.id"
      class="absolute w-full h-full"
      style="top:0;left:0px;z-index:-1"
    ></canvas>
  </div>
</template>
<script>
import Dancer from "vudio.js";

export default {
  props: ["media"],

  data() {
    return {
      dancer: null
    };
  },

  methods: {
    handlePlay() {
      if (!this.dancer) {
        this.setDancer();
      }
      this.dancer.dance();
      this.pauseAllOthers();
    },
    setDancer() {
      let audio = document.querySelector(`#audio-${this.media.id}`);
      let canvas = document.querySelector(`#canvas-${this.media.id}`);
      let dancer = new Dancer(audio, canvas, {
        effect: "waveform",
        accuracy: 128,
        waveform: {
          maxHeight: 80,
          minHeight: 1,
          spacing: 1,
          color: "#fb7299",
          shadowBlur: 0,
          shadowColor: "#fb7299",
          fadeSide: true,
          horizontalAlign: "center",
          verticalAlign: "middle"
        }
      });
      this.dancer = dancer;
    },
    pauseAllOthers() {
      let audios = document.querySelectorAll("audio");
      for (let index = 0; index < audios.length; index++) {
        const audio = audios[index];
        if (audio.id != `audio-${this.media.id}`) {
          audio.pause();
        }
      }
    }
  }
};
</script>

<style lang="scss">
audio {
  border-left: 2px solid var(--color-pink);
}

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
  padding-left: 4px;
}
</style>
