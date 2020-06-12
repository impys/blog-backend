<template>
  <div class="flex p-2 w-full relative" id="editor-box">
    <div
      id="editor"
      class="flex p-0 rounded w-full"
      @click="handelClickMarkdownTextarea"
    >
      <textarea
        id="markdown-textarea"
        class="p-3 rounded"
        :style="textareaStyle"
        @keydown.tab="tabIndent"
        @paste="uploadFileByPaste"
        v-model="value"
      ></textarea>
      <div v-if="showPreview" id="markdown-preview" class="w-1/2 p-3 markdown-github" v-html="markedBody"></div>
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

    <div
      id="previewBtn"
      class="absolute flex flex-col"
      style="top:8px;right:58px"
      @click="showPreview = !showPreview"
    >
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
        <path
          class="heroicon-ui"
          d="M17.56 17.66a8 8 0 0 1-11.32 0L1.3 12.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95zm-9.9-1.42a6 6 0 0 0 8.48 0L20.38 12l-4.24-4.24a6 6 0 0 0-8.48 0L3.4 12l4.25 4.24zM11.9 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"
        />
      </svg>
    </div>
  </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";
import { md } from "../md";

const UPLOAD_API = "/nova-vendor/post-editor/upload";

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ["resourceName", "resourceId", "field"],

  data() {
    return {
      showPreview: false
    };
  },

  computed: {
    markedBody: function() {
      if (this.value) {
        return md.render(this.value);
      }
    },
    textareaStyle() {
      return {
        width: this.showPreview ? "50%" : "100%"
      };
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
        behavior: "smooth"
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
    word-break: break-all;
    padding-bottom: 600px;
    text-align: justify;
  }
  #markdown-preview {
    overflow: scroll;
  }
}

#uploader,
#previewBtn {
  height: 50px;
  width: 50px;
  display: flex;
  justify-content: center;
  align-items: center;

  font-size: 12px;
  cursor: pointer;

  label {
    height: 100px;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
  }
}

#uploader {
  background-color: var(--primary);
  border-top-right-radius: 0.25rem;
  svg path,
  svg rect {
    fill: var(--white);
  }
}
#previewBtn {
  border-bottom-left-radius: 0.25rem;
  background-color: var(--white);
  border: 1px solid var(--primary);
  svg path,
  svg rect {
    fill: var(--primary);
  }
}
</style>
