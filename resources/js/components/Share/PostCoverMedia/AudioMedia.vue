<template>
  <div class="rounded-t relative hidden lg:block h-16">
    <div class="flex items-center absolute h-16 w-full h-10 top-0 left-0 z-10">
      <div class="text-3xl text-grey hover:text-pink-400 cursor-default" @click.stop="playOrPause()">
        <svg class="icon">
          <use xlink:href="#icon-play-circle-outline" v-if="!playing" />
          <use xlink:href="#icon-pause-circle-outline" v-if="playing" />
        </svg>
      </div>
    </div>
    <audio :id="'audio-' + media.id" class="hidden">
      <source :src="media.url" />
    </audio>
    <canvas :id="'canvas-' + media.id" class="absolute w-full h-full top-0 left-0"></canvas>
  </div>
</template>
<script>
import Dancer from "vudio.js";

export default {
  props: ["media"],

  data() {
    return {
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
    playOrPause() {
      this.playing ? this.pause() : this.play();
    },
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
          maxHeight: 64,
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
