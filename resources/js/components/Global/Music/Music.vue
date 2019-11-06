<template>
  <div class="relative">
    <aplayer ref="aplayer" id="aplayer" :autoplay="autoplay" :music="music" :list="list" />
    <div
      class="border-gray-300 rounded-full border h-6 absolute w-1/4 mr-2"
      style="top:14px;right:2px"
    >
      <input
        type="text"
        v-model="query"
        @keyup.enter="search"
        class="outline-none border-transparent text-xs px-2 bg-transparent h-atuo w-full"
      />
    </div>
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
      query: "",
      autoplay: false,
      list: [
        {
          title: "1",
          artist: "1",
          src: "null"
        },
        {
          title: "1",
          artist: "1",
          src: "null"
        },
        {
          title: "1",
          artist: "1",
          src: "null"
        }
      ],
      music: {
        title: null,
        artist: null,
        src: "null"
      }
    };
  },
  methods: {
    playMusic(music) {
      this.music = music;
      this.list.push(music);
      this.$refs.aplayer.thenPlay();
    },
    search() {}
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
  .aplayer-info {
    border-top: 1px solid #e2e8f0; //gray-300
    border-right: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
  }
  .aplayer-list {
    border-left: 1px solid #e2e8f0;
    border-right: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
  }
}
</style>
