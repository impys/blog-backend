<template>
  <div class="custom__markdown-eeditor">
    <pre class="hidden-pre">{{ value }}<br /></pre>
    <textarea
      id="markdown-textarea"
      v-on:keydown.tab="tabIndent"
      @paste.prevent="onPaste"
      v-model="value"
    ></textarea>
  </div>
</template>

<script>
export default {
  props: ["value"],
  methods: {
    tabIndent(event) {
      if (event.keyCode == 9) {
        event.preventDefault();
        this.insertStringToTextarea(" " + " " + " " + " ");
      }
    },
    onPaste(e) {
      let file = e.clipboardData.items[0].getAsFile();
      if (file) {
        let formData = new FormData();
        formData.append("image", file);
        let config = {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        };
        axios
          .post(this.submitUploadArticleImagesUrl, formData, config)
          .then(res => {
            this.insertStringToTextarea("![](" + res.data.url + ")\n");
            this.article.body = document.getElementById(
              "markdown-textarea"
            ).value;
          });
      }
    },
    insertStringToTextarea(string) {
      var textarea = document.getElementById("markdown-textarea");
      var value = textarea.value.split("");
      var pos = textarea.selectionStart;
      var insertValue = string;
      value.splice(pos, 0, insertValue);
      textarea.value = value.join("");
      // 定位新的光标位置
      textarea.selectionStart = textarea.selectionEnd =
        pos + insertValue.length;
      textarea.focus();
    }
  }
};
</script>

<style lang="scss">
.custom__markdown-eeditor {
  position: relative;
  min-height: 100vh;
  overflow: hidden;
  border-radius: 0.5rem;
  border: #bacad6 1px solid;

  .hidden-pre {
    display: inline-block;
    visibility: hidden;
    padding: 12px;
    font-size: 20px;
    width: 100%;
    height: 100%;
    line-height: normal;
  }

  textarea {
    line-height: normal;
    padding: 12px;
    color: #7c858e;
    outline: none;
    height: 100%;
    border: none;
    resize: none;
    width: 100%;
    position: absolute;
    top: 0;
    left: 0;
  }
}
</style>
