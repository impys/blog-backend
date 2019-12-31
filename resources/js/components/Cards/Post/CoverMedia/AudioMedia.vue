<template>
  <div class="rounded-t relative">
    <div class="flex items-center h-16 pl-3 w-full z-10 px-1 sm:px-1 md:px-3 lg:px-3">
      <i
        class="fas fa-play text-lg cursor-pointer text-grey hover:text-pink"
        v-if="!playing"
        @click="play()"
      ></i>
      <i
        class="fas fa-pause text-lg cursor-pointer text-grey hover:text-pink"
        v-if="playing"
        @click="pause()"
      ></i>
    </div>
    <audio :id="'audio-' + media.id" class="hidden">
      <source :src="media.url" />
    </audio>
    <canvas
      :id="'canvas-' + media.id"
      class="absolute w-full h-full"
      style="top:0;left:0px;z-index:-1"
    ></canvas>
  </div>
</template>
<script>
import Dancer from "vudio.js";

export default {
  props: ["post"],

  data() {
    return {
      media: this.post.cover_media,
      dancer: null,
      playing: false
    };
  },

  mounted() {
    EventHub.$on("pause", audio => {
      this.pause(audio);
    });
  },

  methods: {
    play() {
      let audio = this.getAudio();
      this.pauseAllOtherAudios();
      this.playing = true;
      audio.play();
      this.dance();
    },

    dance() {
      if (!this.dancer) {
        this.setDancer();
      }
      this.dancer.dance();
    },

    pause(audio = this.getAudio()) {
      this.playing = false;
      audio.pause();
    },

    setDancer() {
      let audio = this.getAudio();
      let canvas = this.getCanvas();
      this.dancer = new Dancer(audio, canvas, {
        effect: "waveform",
        accuracy: 128,
        waveform: {
          maxHeight: 80,
          minHeight: 0,
          spacing: 1,
          color: "#fb7299",
          shadowBlur: 0,
          shadowColor: "#fb7299",
          fadeSide: true,
          horizontalAlign: "start",
          verticalAlign: "bottom"
        }
      });
    },

    getAudio() {
      return document.querySelector(`#audio-${this.media.id}`);
    },

    getCanvas() {
      return document.querySelector(`#canvas-${this.media.id}`);
    },

    pauseAllOtherAudios() {
      let otherPlayingAudios = this.getOtherPlayingAudios();

      for (const key in otherPlayingAudios) {
        const audio = otherPlayingAudios[key];
        console.log(audio);
        EventHub.$emit("pause", audio);
      }
    },

    getOtherPlayingAudios() {
      return Array.from(document.querySelectorAll("audio")).filter(audio => {
        return !audio.paused && audio.id != `audio-${this.media.id}`;
      });
    }
  }
};
</script>

<style lang="scss">
