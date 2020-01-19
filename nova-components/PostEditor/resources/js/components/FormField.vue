<template>
  <div class="flex p-2 w-full" id="editor-box">
    <div id="editor" class="flex p-0 form-input-bordered rounded" style="width:92%">
      <textarea
        id="markdown-textarea"
        class="p-3 rounded"
        @keydown.tab="tabIndent"
        @paste="uploadFileByPaste"
        v-model="value"
        @click="handelClickMarkdownTextarea"
      ></textarea>
      <div id="markdown-preview" class="w-1/2 p-3 border-l border-60 markdown-github" v-html="markedBody"></div>
    </div>

    <div class="relative ml-2" style="width:8%">
      <div class="sticky" style="top:10px">
        <div class="flex flex-col">
          <div id="upload-icon" class="form-input-bordered">
            <label for="file-input">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="60" height="60">
                <path
                  class="heroicon-ui"
                  d="M13 5.41V17a1 1 0 0 1-2 0V5.41l-3.3 3.3a1 1 0 0 1-1.4-1.42l5-5a1 1 0 0 1 1.4 0l5 5a1 1 0 1 1-1.4 1.42L13 5.4zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z"
                />
              </svg>
            </label>
            <input
              type="file"
              class="hidden"
              id="file-input"
              ref="fileInput"
              @change="uploadFileByClickButton"
            />
          </div>
          <div class="relative my-2" v-if="showProgress">
            <progress id="progress-bar" class="w-full" value="0" max="100"></progress>
            <div id="progress-label" class="absolute" style="top:0;left:4px">0%</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";
import marked from "marked";

const UPLOAD_API = "/upload";

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ["resourceName", "resourceId", "field"],

  data() {
    return {
      showProgress: false
    };
  },

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
        onUploadProgress: self.handleProgress
      };
      this.showProgress = true;
      this.$toasted.info("正在上传", { duration: 0 });
      axios
        .post(UPLOAD_API, formData, config)
        .then(res => {
          this.$toasted.clear();
          this.$toasted.success("上传成功");
          this.insertStringToTextarea(res.data.data.markdown_dom);
        })
        .catch(e => {
          this.$toasted.error("上传失败");
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

    handleProgress(e) {
      let progressBar = document.getElementById("progress-bar");
      progressBar.value = 0;
      if (e.lengthComputable) {
        let percent = Math.round((e.loaded * 100) / e.total);

        document.getElementById("progress-label").innerHTML =
          percent.toFixed(2) + "%";

        progressBar.max = e.total;
        progressBar.value = e.loaded;
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
  height: 700px;
  textarea {
    line-height: normal;
    color: #7c858e;
    outline: none;
    height: 100%;
    border: none;
    resize: none;
    width: 50%;
  }
  #markdown-preview {
    overflow: scroll;
  }
}

#upload-icon {
  height: 80px;
  display: flex;
  justify-content: center;
  align-items: center;

  svg path,
  svg rect {
    fill: #e3e7eb;
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

progress::-webkit-progress-bar {
  border-radius: 10px;
  background-color: var(--white);
  border: 1px solid var(--success);
}

progress::-webkit-progress-value {
  border-radius: 10px;
  background-color: var(--success);
}
</style>
