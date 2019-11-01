<template>
  <div
    class="ml-4 sm:w-full lg:w-1/4 w-full border bg-white border-gray-700 rounded-lg flex flex-col"
    v-bind:class="[loaded ? 'h-vh-96' : 'h-10']"
  >
    <div class="flex h-10 flex-row items-center min-h-10">
      <transition name="slide-fade" mode="out-in">
        <i class="fa fa-search text-lg block mx-2 text-red-400" v-if="loading" key="unloading"></i>

        <div title="0" v-if="!loading">
          <svg
            version="1.1"
            id="loader-1"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
            x="0px"
            y="0px"
            width="30px"
            height="30px"
            viewBox="0 0 40 40"
            enable-background="new 0 0 40 40"
            xml:space="preserve"
          >
            <path
              opacity="0.2"
              fill="#000"
              d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946
    s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634
    c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"
            />
            <path
              fill="#000"
              d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0
    C22.32,8.481,24.301,9.057,26.013,10.047z"
            >
              <animateTransform
                attributeType="xml"
                attributeName="transform"
                type="rotate"
                from="0 20 20"
                to="360 20 20"
                dur="0.5s"
                repeatCount="indefinite"
              />
            </path>
          </svg>
        </div>
      </transition>
      <input
        type="text"
        class="outline-none border-transparent text-base pr-4 bg-transparent h-6 w-full"
      />
      <button @click="on" class="w-4 h-4 text-sm px-2">on</button>
    </div>
    <!-- <transition name="slide-fade" mode="out-in"> -->
    <div class="border-t custom__search-input overflow-scroll" v-if="loaded">
      测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />测试
      <br />
    </div>
    <!-- </transition> -->
  </div>
</template>


<script>
export default {
  data() {
    return {
      loading: true,
      loaded: false
    };
  },

  methods: {
    on() {
      this.loading = !this.loading;
      this.loaded = !this.loaded;
    }
  }
};
</script>

<style lang="scss">
.custom__search-input {
  transition: height 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 1.2s cubic-bezier(1, 0.5, 0.8, 1);
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}

.slide-fade-enter-active {
  transition: all 0.2s;
}
.slide-fade-leave-active {
  transition: all 0.2s;
}
.slide-fade-enter {
  transform: translateX(-10px);
  opacity: 0;
}
.slide-fade-leave-to {
  transform: translateX(-10px);
  opacity: 0;
}

.h-vh-96 {
  height: 96vh;
}
/*
  Set the color of the icon
*/
svg path,
svg rect {
  fill: rgb(252, 129, 129);
}
</style>
