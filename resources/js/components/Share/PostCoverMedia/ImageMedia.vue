<template>
  <div
    class="h-48 lg:h-64 w-full bg-cover bg-top rounded cover-image"
    :style="style"
    @mousemove="handleMouseMove"
  ></div>
</template>
<script>
export default {
  props: ["media"],
  data() {
    return {
      //背景图所在dom的宽
      coverClientWidth: 0,

      //背景图所在dom的高
      coverClientHeight: 0,

      //背景图按比例缩放之后的高
      imageScaleHeight: 0,

      mouseOffsetImageY: 0
    };
  },
  computed: {
    style() {
      let backgroundPositionY =
        (this.mouseOffsetImageY / this.coverClientHeight) *
        (this.imageScaleHeight - this.coverClientHeight);
      return {
        backgroundImage: `url(${this.media.url})`,
        backgroundPosition: `0px -${backgroundPositionY}px`
      };
    }
  },

  mounted() {
    this.init();
  },

  methods: {
    init() {
      this.setCoverClientWidth();
      this.setCoverClientHeight();
      this.setImageScaleHeight();
    },

    //背景图所在dom的宽
    setCoverClientWidth() {
      this.coverClientWidth = document.querySelector(
        ".cover-image"
      ).clientWidth;
    },

    //背景图所在dom的高
    setCoverClientHeight() {
      this.coverClientHeight = document.querySelector(
        ".cover-image"
      ).clientHeight;
    },

    //背景图按比例缩放之后的高
    setImageScaleHeight() {
      this.imageScaleHeight =
        (this.media.height / this.media.width) * this.coverClientWidth;
    },

    handleMouseMove(e) {
      this.mouseOffsetImageY = e.offsetY;
    }
  }
};
</script>
