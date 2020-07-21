<template>
  <div
    class="editable"
    v-text="textContent"
    :contenteditable="true"
    @focus="isLocked = true"
    @blur="isLocked = false"
    @input="changeText"
    @keydown.191.stop
  ></div>
</template>
<script>
export default {
  name: "editDiv",
  props: {
    value: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      textContent: this.value,
      isLocked: false
    };
  },
  watch: {
    value() {
      if (!this.isLocked || !this.textContent) {
        this.textContent = this.value;
      }
    }
  },
  methods: {
    changeText() {
      this.$emit("input", this.$el.textContent);
    }
  }
};
</script>
<style lang="scss">
.editable {
  width: 100%;
  height: 100%;
  word-break: break-all;
  outline: none;
  user-select: text;
  white-space: pre-wrap;
  text-align: left;
  &[contenteditable="true"] {
    -webkit-user-modify: read-write-plaintext-only;
    &:empty:before {
      content: attr(placeholder);
      display: block;
      color: #ccc;
    }
  }
}
</style>
