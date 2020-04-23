<template>
  <div class="flex p-2 w-full relative" id="editor-box">
    <div
      id="editor"
      class="flex p-0 rounded markdown-github w-full"
      @click="handelClickMarkdownTextarea"
    >
      <textarea
        id="markdown-textarea"
        class="p-3 rounded"
        @keydown.tab="tabIndent"
        @paste="uploadFileByPaste"
        v-model="value"
      ></textarea>
      <div id="markdown-preview" class="w-1/2 p-3" v-html="markedBody"></div>
    </div>

    <div id="uploader" class="absolute flex flex-col" style="top:8px;right:8px">
      <label for="file-input" class="relative">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
          <path
            class="heroicon-ui"
            d="M13 5.41V17a1 1 0 0 1-2 0V5.41l-3.3 3.3a1 1 0 0 1-1.4-1.42l5-5a1 1 0 0 1 1.4 0l5 5a1 1 0 1 1-1.4 1.42L13 5.4zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z"
          />
        </svg>
        <div id="uploader-progress-label" class="absolute" style="bottom:0px;"></div>
      </label>
      <input
        type="file"
        class="hidden"
        id="file-input"
        ref="fileInput"
        @change="uploadFileByClickButton"
      />
    </div>
  </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";
import marked from "marked";

const UPLOAD_API = "/nova-vendor/post-editor/upload";

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ["resourceName", "resourceId", "field"],

  computed: {
    markedBody: function() {
      if (this.value) {
        return marked(this.value);
      }
    }
  },

  watch: {
    value: function(value) {
      this.$nextTick(() => Prism.highlightAll());
    }
  },

  methods: {
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value || "";
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, this.value || "");
    },

    /**
     * Update the field's internal value.
     */
    handleChange(value) {
      this.value = value;
    },

    uploadFileByClickButton() {
      let self = this;
      let file = this.$refs.fileInput.files[0];
      if (file) {
        this.uploadFile(file);
      }
    },

    uploadFileByPaste(e) {
      let file = e.clipboardData.items[0].getAsFile();
      if (file) {
        this.uploadFile(file);
      }
    },

    uploadFile(file) {
      let self = this;
      let formData = new FormData();
      formData.append("file", file);
      let config = {
        headers: {
          "Content-Type": "multipart/form-data"
        },
        onUploadProgress: self.handleOnUploadProgress
      };

      this.$toasted.info("正在上传", { duration: 0 });

      Nova.request()
        .post(UPLOAD_API, formData, config)
        .then(res => {
          this.$toasted.clear();
          this.$toasted.success("上传成功");
          this.insertStringToTextarea(res.data.data.markdown_dom);
          this.$refs.fileInput.value = "";
        })
        .catch(e => {
          console.log(e);
          this.$toasted.clear();
          this.$toasted.error("上传失败");
          this.$refs.fileInput.value = "";
        });
    },

    tabIndent(event) {
      if (event.keyCode == 9) {
        event.preventDefault();
        this.insertStringToTextarea(" " + " " + " " + " ");
      }
    },

    insertStringToTextarea(string) {
      let textarea = document.getElementById("markdown-textarea");
      let value = textarea.value.split("");
      let pos = textarea.selectionStart;
      let insertValue = string;
      value.splice(pos, 0, insertValue);
      textarea.value = value.join("");
      // 定位新的光标位置
      textarea.selectionStart = textarea.selectionEnd =
        pos + insertValue.length;
      textarea.focus();
      this.value = textarea.value;
    },

    handleOnUploadProgress(e) {
      let progressLabel = document.getElementById("uploader-progress-label");
      progressLabel.value = 0;
      if (e.lengthComputable) {
        let percent = Math.round((e.loaded * 100) / e.total);
        progressLabel.innerHTML = percent.toFixed(2) + "%";
        progressLabel.max = e.total;
        progressLabel.value = e.loaded;
      }
    },

    handelClickMarkdownTextarea() {
      document.querySelector("#editor-box").scrollIntoView({
        block: "start",
        behavior: "auto"
      });
    }
  }
};
</script>


<style lang="scss">
#editor {
  height: 670px;
  border-width: 1px;
  border-color: #bacad6;
  textarea {
    line-height: normal;
    outline: none;
    height: 100%;
    border: none;
    resize: none;
    width: 50%;
    word-break: break-all;
  }
  #markdown-preview {
    overflow: scroll;
  }
}

#uploader {
  height: 50px;
  width: 50px;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: var(--primary);
  color: var(--white);
  border-bottom-left-radius: 0.25rem;
  border-top-right-radius: 0.25rem;
  font-size: 12px;

  svg path,
  svg rect {
    fill: #fff;
  }

  label {
    height: 100px;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
  }
}
</style>
