<template>
  <div class="relative">
    <aplayer
      ref="aplayer"
      id="aplayer"
      :autoplay="autoplay"
      :music="music"
      :list="list"
      theme="#fc8181"
    />
    <div
      class="border-gray-300 rounded-full border h-6 absolute w-1/4 z-10 bg-white"
      style="top:8px;right:2px;"
    >
      <input
        type="text"
        v-model="query"
        @keyup.enter="search"
        class="outline-none border-transparent text-xs px-2 bg-transparent h-atuo w-full pr-6"
      />
    </div>
    <loading-icon key="loading" class="absolute z-10" v-if="loading" style="top:7px;right:-1px;"></loading-icon>
  </div>
</template>

<script>
const SEARCH_API = "/search-music";
import Aplayer from "vue-aplayer";
import LoadingIcon from "./LoadingIcon";

export default {
  components: {
    Aplayer,
    LoadingIcon
  },
  mounted() {
    EventHub.$on("clickMusic", music => {
      this.playMusic(music);
    });
  },
  data() {
    return {
      query: "",
      loading: false,
      autoplay: false,
      list: [],
      searchList: [],
      showSearchList: false,
      music: {
        title: "世间美好与你环环相扣",
        artist: "柏松",
        src:
          "https://asset.moreless.blog/file/moreless-public/default/default.mp3"
      }
    };
  },
  methods: {
    playMusic(music) {
      this.music = music;
      this.list.push(music);
      this.$refs.aplayer.thenPlay();
    },
    search() {
      if (!this.query.trim()) {
        return;
      }
      if (this.loading == true) {
        return;
      }
      this.loading = true;
      axios
        .get(SEARCH_API, {
          params: {
            query: this.query.trim()
          }
        })
        .then(res => {
          this.loading = false;
          this.list = res.data.data;
        })
        .catch(e => {
          this.loading = false;
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
    font-size: 0.75rem !important;
  }
  .aplayer-title {
    font-size: 0.875rem !important;
  }
  .aplayer-info {
    padding-right: 0;
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
  .aplayer-volume-wrap {
    display: none !important;
  }
  .aplayer-icon-mode {
    margin-left: 4px !important;
  }
}
</style>
