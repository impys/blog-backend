<template>
  <div class="custom__markdown-editor flex justify-between">
    <div class="custom__markdown-editor-content w-3/4">
      <pre class="hidden-pre">{{ value }}<br /></pre>
      <textarea
        id="markdown-textarea"
        v-on:keydown.tab="tabIndent"
        @paste.prevent="onPaste"
        v-model="value"
      ></textarea>
    </div>
    <div class="w-1/4 relative ml-4">
      <div class="sticky h-10 border" style="top:0">
        <input type="file" ref="fileInput" @change="uploadFile" />
      </div>
    </div>
  </div>
</template>

<script>
const UPLOAD_API = "/upload";
export default {
  props: ["value"],

  data() {
    return {
      file: ""
    };
  },

  methods: {
    fillFile() {},

    uploadFile() {
      let file = this.$refs.fileInput.files[0];
      let formData = new FormData();
      formData.append("file", file);
      let config = {
        headers: {
          "Content-Type": "multipart/form-data"
        }
      };
      axios.post(UPLOAD_API, formData, config).then(res => {
        //   this.insertStringToTextarea("![](" + res.data.url + ")\n");
        //   this.value = document.getElementById("markdown-textarea").value;
      });
    },

    onPaste(e) {
      e.preventDefault();
      let file = e.clipboardData.items[0].getAsFile();
      if (file) {
        let formData = new FormData();
        formData.append("file", file);
        let config = {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        };
        axios.post(UPLOAD_API, formData, config).then(res => {
          this.insertStringToTextarea("![](" + res.data + ")\n");
          this.value = document.getElementById("markdown-textarea").value;
        });
      }
    },

    tabIndent(event) {
      if (event.keyCode == 9) {
        event.preventDefault();
        this.insertStringToTextarea(" " + " " + " " + " ");
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
.custom__markdown-editor {
  .custom__markdown-editor-content {
    border-radius: 0.5rem;
    border: #bacad6 1px solid;
    position: relative;
    border-radius: 0.5rem;
    min-height: 0vh;
    overflow: hidden;

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
}
</style>
