<template>
  <div>
    <aplayer
      ref="aplayer"
      v-if="show"
      id="aplayer"
      :mini="true"
      :autoplay="autoplay"
      :music="music"
    />
  </div>
</template>

<script>
import Aplayer from "vue-aplayer";

export default {
  components: {
    Aplayer
  },
  mounted() {
    EventHub.$on("clickMusic", music => {
      this.playMusic(music);
    });
  },
  data() {
    return {
      autoplay: false,
      show: false,
      music: {
        title: null,
        artist: null,
        src: "null"
      }
    };
  },
  methods: {
    playMusic(music) {
      this.show = true;
      this.music = music;
      this.$nextTick(() => {
        this.$refs.aplayer.thenPlay();
      });
    }
  }
};
</script>


<style lang="scss">
#aplayer {
  box-shadow: none;
  margin: 0 !important;
  * {
    outline: none;
  }
  .aplayer-pic {
    background-image: none;
    background-color: #fc8181;
  }

  .aplayer-pic .aplayer-pause .aplayer-icon-pause {
    position: absolute;
    top: 3px;
    left: 3px;
    height: 20px;
    width: 20px;
  }

  .aplayer-pic .aplayer-pause {
    width: 26px;
    height: 26px;
    border: 2px solid #fff;
    bottom: 50%;
    right: 50%;
    margin: 0 -15px -15px 0;
  }
}
</style>
