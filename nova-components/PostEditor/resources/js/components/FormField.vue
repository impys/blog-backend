<template>
  <div id="markdown-wrap" class="flex rounded w-full">
    <editable
      id="markdown-editor"
      ref="markdownEditor"
      v-model="value"
      @keydown.tab.native="tabIndent"
      @keydown.esc.native="closeSearch"
      @paste.native="uploadFileByPaste"
      @click.native="handelClickMarkdownEditor"
      @focus.native="handleMarkdownEditorFocus"
      @blur.native="handleMarkdownEditorBlur"
    ></editable>
    <div
      id="markdown-preview"
      class="markdown-github"
      v-html="markedBody"
      @mouseenter="handleMouseEntryPreview"
      @mouseleave="handleMouseLeavePreview"
    ></div>

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

    <!-- Search result modal -->
    <div
      v-on-clickaway="closeSearch"
      v-if="showSearchResultWrap"
      id="search-result-wrap"
      class="overflow-hidden rounded-lg shadow-lg w-full overflow-y-auto"
    >
      <!-- Loader -->
      <div v-if="searchLoading" class="bg-white py-4 overflow-hidden shadow-lg w-full">
        <loader class="text-60" width="52" />
      </div>

      <!-- No Results Found -->
      <div v-if="shouldShowNoResults" class="bg-white overflow-hidden shadow-lg w-full">
        <h3
          class="text-xs uppercase tracking-wide text-80 bg-40 py-4 px-3"
        >{{ __('No Results Found.') }}</h3>
      </div>

      <div v-for="(group,index) in formattedSearchResults" :key="index">
        <h3
          class="text-xs uppercase tracking-wide text-80 bg-40 py-2 px-3"
        >{{ group.resourceTitle }}</h3>

        <ul class="list-reset">
          <li v-for="item in group.items" :key="item.resourceName + ' ' + item.index">
            <div
              class="cursor-pointer flex items-center hover:bg-20 block py-2 px-3 no-underline font-normal"
              @click="handleClickResource(item)"
            >
              <p class="text-90">{{ item.title }}</p>
              <p v-if="item.subTitle" class="text-xs mt-1 text-80">{{ item.subTitle }}</p>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";
import { md } from "../md";
import { mixin as clickaway } from "vue-clickaway";

import Editable from "./Editable";

const UPLOAD_API = "/nova-vendor/post-editor/upload";

const SEARCH_API = "/nova-vendor/post-editor/search";

const PAGE_CONTAINER_TRIGGER = ":::page";

export default {
  mixins: [FormField, HandlesValidationErrors, clickaway],

  props: ["resourceName", "resourceId", "field"],

  components: {
    Editable,
  },

  data() {
    return {
      isShowPreview: false,
      lastRange: null,
      initialValue: "🍭",
      isHoverPreview: false,
      editorMaxBoundingClientRect: 0,
      searchResults: [],
      searchLoading: false,
      showSearchResultWrap: false,
    };
  },

  mounted() {
    this.setFormStyle();
    this.$nextTick(function () {
      this.setMarkdownPreviewWidth();
    });
    this.setEditorMaxBoundingClientRect();
    this.registerListener();
  },

  destroyed() {
    window.removeEventListener("scroll", this.handleMarkdownEditorScroll);
    this.getMarkdownPreview().removeEventListener(
      "scroll",
      this.handleMarkdownPreviewScroll
    );
  },

  watch: {
    value() {
      this.debounceSearchIfNeed();
    },
  },

  computed: {
    markedBody: function () {
      if (this.value) {
        return md.render(this.value);
      }
    },

    vh() {
      return Math.max(
        document.documentElement.clientHeight || 0,
        window.innerHeight || 0
      );
    },

    hasSearchResults() {
      return this.searchResults.length > 0;
    },

    shouldShowNoResults() {
      return !this.hasSearchResults && !this.searchLoading;
    },

    indexedSearchResults() {
      return _.map(this.searchResults, (item, index) => {
        return { index, ...item };
      });
    },

    formattedSearchGroups() {
      return _.chain(this.indexedSearchResults)
        .map((item) => {
          return {
            resourceName: item.resourceName,
            resourceTitle: item.resourceTitle,
          };
        })
        .uniqBy("resourceName")
        .value();
    },

    formattedSearchResults() {
      return _.map(this.formattedSearchGroups, (group) => {
        return {
          resourceName: group.resourceName,
          resourceTitle: group.resourceTitle,
          items: _.filter(
            this.indexedSearchResults,
            (item) => item.resourceName == group.resourceName
          ),
        };
      });
    },
  },

  methods: {
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value || this.initialValue;
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

    /**
     * 点击上传按钮上传文件
     */
    uploadFileByClickButton() {
      let self = this;
      let file = this.$refs.fileInput.files[0];
      if (file) {
        this.uploadFile(file);
      }
    },

    /**
     * 通过粘贴上传文件
     */
    uploadFileByPaste(e) {
      let file = e.clipboardData.items[0].getAsFile();
      if (file) {
        this.uploadFile(file);
      }
    },

    /**
     * 上传文件
     */
    uploadFile(file) {
      let self = this;
      let formData = new FormData();
      formData.append("file", file);
      let config = {
        headers: {
          "Content-Type": "multipart/form-data",
        },
        onUploadProgress: self.handleOnUploadProgress,
      };

      this.$toasted.info("正在上传", { duration: 0 });

      Nova.request()
        .post(UPLOAD_API, formData, config)
        .then((res) => {
          this.$toasted.clear();
          this.$toasted.success("上传成功");
          this.insertStringToEditor(res.data.data.markdown_dom);
          this.$refs.fileInput.value = "";
        })
        .catch((e) => {
          console.log(e);
          this.$toasted.clear();
          this.$toasted.error("上传失败");
          this.$refs.fileInput.value = "";
        });
    },

    handleMouseEntryPreview() {
      this.isHoverPreview = true;
    },

    handleMouseLeavePreview() {
      this.isHoverPreview = false;
    },

    /**
     * set editor 上边距与视口上边距最大距离
     */
    setEditorMaxBoundingClientRect() {
      this.editorMaxBoundingClientRect = this.getMarkdownEditor().getBoundingClientRect().top;
    },

    registerListener() {
      window.addEventListener("scroll", this.handleMarkdownEditorScroll);
      this.getMarkdownPreview().addEventListener(
        "scroll",
        this.handleMarkdownPreviewScroll
      );
    },

    handleMarkdownEditorScroll() {
      // 如果是 preview 主动滚动，则阻止 editor
      if (this.isHoverPreview) return;

      this.getMarkdownPreview().scrollTop =
        (this.getEditorScrollTop() * this.getMaxPreviewScrollTop()) /
        this.getMaxEditorScrollTop();
    },

    handleMarkdownPreviewScroll() {
      // 如果是 editor 主动滚动，则阻止 preview
      if (!this.isHoverPreview) return;

      document.documentElement.scrollTop =
        (this.getPreviewScrollTop() *
          (this.getMaxEditorScrollTop() + this.editorMaxBoundingClientRect)) /
        this.getMaxPreviewScrollTop();
    },

    getMaxEditorScrollTop() {
      return this.getMarkdownEditor().clientHeight - this.vh;
    },

    getMaxPreviewScrollTop() {
      return this.getMarkdownPreview().scrollHeight - this.vh;
    },

    /**
     * 获取 Editor 元素的卷起高度
     * 不能直接通过 scrollTop 获得，所以通过 getBoundingClientRect 变相获取
     * https://developer.mozilla.org/zh-CN/docs/Web/API/Element/getBoundingClientRect
     * 如果 Editor 上边沿在视口上边沿下放，则为负数，但是最小为0
     */
    getEditorScrollTop() {
      return Math.max(-this.getMarkdownEditor().getBoundingClientRect().top, 0);
    },

    getPreviewScrollTop() {
      return this.getMarkdownPreview().scrollTop;
    },

    handlePreview() {
      this.setMarkdownEditorWidth();
      this.setMarkdownPreviewTop();
      this.toggleIsShowPreview();
    },

    getMarkdownEditor() {
      return document.querySelector("#markdown-editor");
    },

    getMarkdownPreview() {
      return document.querySelector("#markdown-preview");
    },

    setMarkdownEditorWidth() {
      this.getMarkdownEditor().style.width = this.isShowPreview
        ? "100%"
        : "50%";
    },

    setMarkdownPreviewTop() {
      this.getMarkdownPreview().style.top = this.isShowPreview ? "100%" : 0;
    },

    toggleIsShowPreview() {
      this.isShowPreview = !this.isShowPreview;
    },

    setMarkdownPreviewWidth() {
      let wrap = document.querySelector("#markdown-wrap");
      this.getMarkdownPreview().style.width = wrap.offsetWidth / 2 + "px";
    },

    setFormStyle() {
      let form = document.querySelector("form");
      form.className = "markdown-form";

      let div = form.children[0];
      div.className = "";

      let buttons = form.children[1];
      buttons.className = "markdown-buttons";

      let cancel = buttons.children[0];
      cancel.innerHTML = "✘";
      cancel.className = "markdown-buttons-cancel";

      let second = buttons.children[1];

      let confirm = buttons.children[2];
      confirm.children[0].innerHTML = "✔︎";
      confirm.className = "markdown-buttons-confirm";

      buttons.removeChild(second);
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
      this.value = this.getMarkdownEditor().textContent;

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

    scrollToTopIfNeeded() {
      if (this.getMarkdownEditor().getBoundingClientRect().top > 0) {
        window.scrollTo({
          top: this.editorMaxBoundingClientRect,
          behavior: "smooth",
        });
      }
    },

    /**
     * 当编辑器失去焦点时，把 range 保存下来，因为一会聚焦的时候还要用
     */
    handleMarkdownEditorBlur() {
      this.lastRange = this.getMarkdownEditorRange();
    },

    getMarkdownEditorSelection() {
      // 返回返回当前 document 对象所关联的 window 对象，如果没有，会返回 null https://developer.mozilla.org/zh-CN/docs/Web/API/Document/defaultView
      let window = this.getMarkdownEditor().ownerDocument.defaultView;

      // 返回一个 Selection 对象，表示用户选择的文本范围或光标的当前位置 https://developer.mozilla.org/zh-CN/docs/Web/API/Window/getSelection
      return window.getSelection();
    },

    getMarkdownEditorRange() {
      return this.getMarkdownEditorSelection().getRangeAt(0);
    },

    handelClickMarkdownEditor() {
      this.scrollToTopIfNeeded();
    },

    handleMarkdownEditorFocus() {
      if (this.value === this.initialValue) {
        this.getMarkdownEditor().innerHTML = null;
        this.value = null;
      }
    },

    handleClickResource(resource) {
      this.closeSearch();

      let selection = this.getMarkdownEditorSelection();

      let range = document.createRange();

      // 将 node 利用 selectNode 放入 range
      range.selectNode(this.lastRange.startContainer);

      // 利用 range deleteContents 方法删除文本
      range.deleteContents();

      let string = `:::page ${resource.resourceName} ${resource.resourceId}
${resource.title}
:::

`;

      this.insertStringToEditor(string);
    },

    /**
     * Debounce function for the search handler
     */
    debouncer: _.debounce((callback) => callback(), 500),

    debounceSearchIfNeed() {
      this.debouncer(() => {
        this.searchIfNeed();
      }, 200);
    },

    searchIfNeed() {
      let selection = this.getMarkdownEditorSelection();

      if (selection.anchorNode && selection.anchorNode.nodeName === "#text") {
        let currentRowText = selection.anchorNode.data;

        if (
          currentRowText &&
          currentRowText.indexOf(PAGE_CONTAINER_TRIGGER) !== -1
        ) {
          let search = currentRowText
            .replace(PAGE_CONTAINER_TRIGGER, "")
            .trim();

          if (search !== "") {
            this.openSearch();

            this.search(search);

            return;
          }
        }
      }

      this.closeSearch();
    },

    openSearch() {
      this.showSearchResultWrap = true;
      this.searchLoading = true;
      this.searchResults = [];
    },

    closeSearch() {
      this.showSearchResultWrap = false;
      this.searchResults = [];
      this.searchLoading = false;
    },

    search(search) {
      Nova.request()
        .get(SEARCH_API, {
          params: { search },
        })
        .then((response) => {
          this.searchResults = response.data;
          this.searchLoading = false;
        })
        .catch((e) => {
          console.log(e);
          this.searchLoading = false;
        });
    },
  },
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

  .markdown-buttons {
    top: 148px;
  }

  #uploader,
  #previewBtn,
  .markdown-buttons-cancel,
  .markdown-buttons-confirm {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    color: white;
    border-bottom-left-radius: 2px;
    background-color: var(--primary);
    margin-bottom: 5px;
    font-size: 12px;
  }

  .markdown-buttons-cancel,
  .markdown-buttons-confirm {
    display: block;
    line-height: 40px;
    text-align: center;
    font-size: 12px;
    position: relative;
    cursor: pointer;
    span {
      svg {
        width: 16px !important;
      }
    }
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
  height: 100vh;
  background-color: white;
  box-shadow: 0 1px 3px 0 rgba(60, 64, 67, 0.05),
    0 4px 8px 3px rgba(60, 64, 67, 0.15);
}

#markdown-editor {
  min-height: 300px;
  padding-bottom: 450px;
  width: 100%;
}

#search-result-wrap {
  background-color: white;
  max-height: 400px;
  width: 320px;
  position: fixed;
  z-index: 10000;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

div[data-testid] {
  padding-bottom: 0;
}
</style>
