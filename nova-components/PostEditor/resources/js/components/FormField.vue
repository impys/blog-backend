<template>
  <div id="markdown-wrap" class="flex rounded w-full">
    <editable
      id="markdown-editor"
      ref="markdownEditor"
      v-model="value"
      placeholder="🍭🍉🍮"
      @keydown.tab.native="tabIndent"
      @paste.native="uploadFileByPaste"
      @click.native="handelClickMarkdownTextarea"
      @blur.native="handleMarkdownEditorBlur"
    ></editable>
    <div id="markdown-preview" class="markdown-github" v-html="markedBody"></div>

    <div class="toolbar">
      <div id="uploader" class="flex flex-col">
        <label for="file-input" class="relative">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16">
            <path
              class="heroicon-ui"
              d="M13 5.41V17a1 1 0 0 1-2 0V5.41l-3.3 3.3a1 1 0 0 1-1.4-1.42l5-5a1 1 0 0 1 1.4 0l5 5a1 1 0 1 1-1.4 1.42L13 5.4zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z"
            />
          </svg>
          <div
            id="uploader-progress-label"
            class="text-white absolute"
            style="bottom:1px;transform:scale(0.7);"
          ></div>
        </label>
        <input
          type="file"
          class="hidden"
          id="file-input"
          ref="fileInput"
          @change="uploadFileByClickButton"
        />
      </div>

      <div id="previewBtn" class="flex flex-col" @click="handlePreview">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16">
          <path
            class="heroicon-ui"
            d="M17.56 17.66a8 8 0 0 1-11.32 0L1.3 12.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95zm-9.9-1.42a6 6 0 0 0 8.48 0L20.38 12l-4.24-4.24a6 6 0 0 0-8.48 0L3.4 12l4.25 4.24zM11.9 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"
          />
        </svg>
      </div>
    </div>
  </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";
import { md } from "../md";
import Editable from "./Editable";

const UPLOAD_API = "/nova-vendor/post-editor/upload";

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ["resourceName", "resourceId", "field"],

  components: {
    Editable
  },

  data() {
    return {
      isShowPreview: false,
      lastRange: null
    };
  },

  mounted() {
    this.setFormStyle();
    this.$nextTick(function() {
      this.setMarkdownPreviewWidth();
    });
  },

  watch: {
    lastSelection(newValue, oldValue) {
      //   console.log(newValue);
    }
  },

  computed: {
    markedBody: function() {
      if (this.value) {
        return md.render(this.value);
      }
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

    handlePreview() {
      this.setMarkdownEditorWidth();
      this.setMarkdownPreviewTop();
      this.toggleIsShowPreview();
    },

    setMarkdownEditorWidth() {
      let editor = document.querySelector("#markdown-editor");
      editor.style.width = this.isShowPreview ? "100%" : "50%";
    },

    setMarkdownPreviewTop() {
      let preview = document.querySelector("#markdown-preview");
      preview.style.top = this.isShowPreview ? "100%" : "50px";
    },

    toggleIsShowPreview() {
      this.isShowPreview = !this.isShowPreview;
    },

    setMarkdownPreviewWidth() {
      let wrap = document.querySelector("#markdown-wrap");
      let preview = document.querySelector("#markdown-preview");
      preview.style.width = wrap.offsetWidth / 2 + "px";
    },

    setFormStyle() {
      let form = document.querySelector("form");
      form.className = "markdown-form";

      let div = form.children[0];
      div.className = "";

      let buttons = form.children[1];
      buttons.className = "markdown-buttons";

      let cancel = buttons.children[0];
      cancel.innerHTML = "";
      cancel.className = "markdown-buttons-cancel";

      let second = buttons.children[1];

      let confirm = buttons.children[2];
      confirm.innerHTML = "";
      confirm.className = "markdown-buttons-confirm";

      buttons.removeChild(second);
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
          this.insertStringToEditor(res.data.data.markdown_dom);
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
        this.insertStringToEditor(" " + " " + " " + " ");
      }
    },

    insertStringToEditor(string) {
      let selection = this.getMarkdownEditorSelection();

      // 返回一个包含当前选区内容的区域对象
      // https://developer.mozilla.org/zh-CN/docs/Web/API/Selection/getRangeAt
      let range = this.lastRange || selection.getRangeAt(0);

      let textNode = document.createTextNode(string);
      range.insertNode(textNode);
      range.setStartAfter(textNode);
      range.setEndAfter(textNode);
      selection.removeAllRanges();
      selection.addRange(range);

      // 更新 value
      this.value = document.querySelector("#uploader-progress-label").innerText;

      // lastRange 用过一次之后就没有意义了，置为 null
      this.lastRange = null;
    },

    handleOnUploadProgress(e) {
      let label = document.querySelector("#uploader-progress-label");
      label.value = 0;
      if (e.lengthComputable) {
        let percent = Math.round((e.loaded * 100) / e.total);
        label.innerHTML = percent.toFixed(0) + "%";
        label.max = e.total;
        label.value = e.loaded;
      }
    },

    handelClickMarkdownTextarea() {
      document.querySelector("#markdown-wrap").scrollIntoView({
        block: "nearest",
        behavior: "smooth"
      });
    },

    /**
     * 当编辑器失去焦点时，把 range 保存下来，因为一会聚焦的时候还要用
     */
    handleMarkdownEditorBlur() {
      this.lastRange = this.getMarkdownEditorRange();
    },

    getMarkdownEditorSelection() {
      let editor = document.querySelector("#markdown-editor");

      // 返回返回当前 document 对象所关联的 window 对象，如果没有，会返回 null https://developer.mozilla.org/zh-CN/docs/Web/API/Document/defaultView
      let window = editor.ownerDocument.defaultView;

      // 返回一个 Selection 对象，表示用户选择的文本范围或光标的当前位置 https://developer.mozilla.org/zh-CN/docs/Web/API/Window/getSelection
      return window.getSelection();
    },

    getMarkdownEditorRange() {
      return this.getMarkdownEditorSelection().getRangeAt(0);
    }
  }
};
</script>


<style lang="scss">
.markdown-form {
  .toolbar,
  .markdown-buttons {
    position: fixed;
    right: 5px;
    width: 40px;
  }

  #uploader,
  #previewBtn,
  .markdown-buttons-cancel,
  .markdown-buttons-confirm {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border-bottom-left-radius: 2px;
    background-color: var(--primary);
    margin-bottom: 5px;

    font-size: 12px;
    cursor: pointer;
  }

  .toolbar {
    top: 238px;
    #uploader,
    #previewBtn {
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

    #uploader,
    #previewBtn {
      svg path,
      svg rect {
        fill: var(--white);
      }
    }
  }

  .markdown-buttons {
    top: 148px;
    .markdown-buttons-cancel,
    .markdown-buttons-confirm {
      display: block;
      position: relative;
      &::after {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        bottom: 0;
        line-height: 40px;
        color: white;
        text-align: center;
      }
    }

    .markdown-buttons-cancel::after {
      content: "✘";
    }
    .markdown-buttons-confirm::after {
      content: "✔︎";
    }
  }
}

#markdown-editor,
#markdown-preview {
  transition: all 0.3s ease;
  padding: 2rem;
}

#markdown-preview {
  overflow: scroll;
  position: fixed;
  top: 100%;
  right: 50px;
  z-index: 9999;
  height: calc(100% - 50px);
  border-top-left-radius: 0.5rem;
  border-top-right-radius: 0.5rem;
  background-color: white;
  box-shadow: 0 1px 3px 0 rgba(60, 64, 67, 0.05),
    0 4px 8px 3px rgba(60, 64, 67, 0.15);
}

#markdown-editor {
  min-height: 300px;
  padding-bottom: 450px;
  width: 100%;
}

div[data-testid] {
  padding-bottom: 0;
}
</style>
