<template>
  <default-field :field="field" :errors="errors" :full-width-content="true">
    <template slot="field">
      <div
        class="flex justify-between"
        :id="field.name"
        :class="errorClasses"
        :placeholder="field.name"
      >
        <div class="custom__markdown-editor w-5/6">
          <pre class="hidden-pre">{{ value }}<br /></pre>
          <textarea
            id="markdown-textarea"
            v-on:keydown.tab="tabIndent"
            @paste="onPaste"
            v-model="value"
          ></textarea>
        </div>
        <div class="w-1/6 relative ml-2">
          <div class="sticky" style="top:10px">
            <div class="flex flex-col">
              <div class="custom__upload-icon">
                <label for="file-input">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                  >
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
                  @change="uploadFile"
                />
              </div>
              <div class="custom__upload-msg" v-if="tip.length">
                <button @click.prevent="copyLink">{{ tip }}</button>
                <input type="text" id="custom__copy" :value="link" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";

const UPLOAD_API = "/upload";

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ["resourceName", "resourceId", "field"],

  mounted() {},

  data() {
    return {
      file: "",
      tip: "",
      link: ""
    };
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

    copyLink() {
      var copyText = document.getElementById("custom__copy");
      copyText.select();
      if (document.execCommand("copy")) {
        this.tip = "复制成功";
      } else {
        this.tip = "复制失败";
      }
    },

    buildAudio(url) {
      return `<audio controls=""><source src="${url}"></audio>`;
    },

    buildVideo(url) {
      return `<video controls=""><source src="${url}"></video>`;
    },

    uploadFile() {
      let file = this.$refs.fileInput.files[0];
      let typePrefix = file.type.slice(0, 5);
      let formData = new FormData();
      formData.append("file", file);
      let config = {
        headers: {
          "Content-Type": "multipart/form-data"
        }
      };
      axios.post(UPLOAD_API, formData, config).then(res => {
        this.tip = "点击复制";
        if (typePrefix == "audio") {
          let link = this.buildAudio(res.data);
          this.link = link;
        }
        if (typePrefix == "video") {
          let link = this.buildVideo(res.data);
          this.link = link;
        }
        if (typePrefix == "image") {
          let link = "![](" + res.data + ")\n";
          this.link = link;
        }
      });
    },

    onPaste(e) {
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
  border-radius: 0.5rem;
  border: #bacad6 1px solid;
  position: relative;
  border-radius: 0.5rem;
  min-height: 400px;
  overflow: hidden;

  .hidden-pre {
    display: inline-block;
    visibility: hidden;
    padding: 12px;
    font-size: 22px;
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
.custom__upload-icon {
  height: 100px;
  border-radius: 10px;
  border: 1px #bacad6 dashed;
  display: flex;
  justify-content: center;
  align-items: center;
  label {
    height: 100px;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
  }
}
.custom__upload-msg {
  margin-top: 10px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  button {
    background-color: rgb(58, 197, 39);
    color: white;
    border: none;
    border-radius: 4px;
    height: 40px;
    width: 100%;
  }
}
</style>
