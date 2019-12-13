<template>
  <div class="mb-8">
    <div>
      <div class="flex items-center mb-3">
        <h1 class="flex-no-shrink text-90 font-normal text-2xl">标签</h1>
      </div>
      <div class="card mb-6 py-3 px-6">
        <div class="flex flex-row">
          <div class="flex flex-col w-1/2">
            <div class="flex items-center">
              <div class="w-1/2 py-4">
                <h4 class="font-normal text-80">新增</h4>
              </div>

              <div class="flex w-1/2 break-words text-80">
                <input
                  id="title"
                  type="text"
                  class="w-3/4 form-control form-input form-input-bordered"
                  @keyup.enter="addTag"
                  v-model="tag"
                />
                <div class="p-2 cursor-pointer ml-2" @click="addTag">新增</div>
              </div>
            </div>

            <div class="flex" v-if="tags.length">
              <div class="w-1/2 py-4">
                <h4 class="font-normal text-80">已选</h4>
              </div>
              <div class="w-1/2 break-words text-80">
                <div class="flex py-2" v-for="(tag,index) in tags" :key="index">
                  <div class="p-2 w-3/4 form-input-bordered">{{ tag }}</div>
                  <div class="p-2 cursor-pointer ml-2" @click="removeTag(tag)">移除</div>
                </div>
              </div>
            </div>
          </div>

          <div
            class="flex flex-row flex-wrap items-center content-start w-1/2"
            v-if="unselectedAllTags.length"
          >
            <div
              class="flex justify-center py-2 w-1/5 break-words text-80"
              v-for="(tag,index) in unselectedAllTags"
              :key="index"
            >
              <div
                class="p-2 w-3/4 cursor-pointer form-input-bordered"
                @click="clickExistentTag(tag)"
              >{{ tag }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const baseUrl = "/nova-vendor/tag-autocomplete";

export default {
  props: ["resourceName", "resourceId", "panel"],

  mounted() {},

  data() {
    return {
      tag: null,
      tags: this.panel.fields[0].tags || [],
      allTags: this.panel.fields[0].allTags || []
    };
  },

  watch: {
    tags: function(newValue, oldValue) {
      this.submit();
    }
  },

  computed: {
    unselectedAllTags: function() {
      return this.allTags.filter(tag => !this.tags.includes(tag));
    }
  },

  methods: {
    addTag() {
      let index = this.tags.findIndex(tag => {
        return tag == this.tag;
      });

      if (index != -1) {
        this.$toasted.show("标签已经存在", { type: "error" });
      } else {
        this.tags.unshift(this.tag);
      }

      this.tag = null;
    },

    removeTag(tag) {
      var index = this.tags.indexOf(tag);
      this.tags.splice(index, 1);
    },

    clickExistentTag(tag) {
      this.tag = tag;
      this.addTag();
    },

    async submit() {
      Nova.request()
        .put(`${baseUrl}/tag-post/${this.resourceId}`, {
          tags: this.tags
        })
        .then(res => {
          this.$toasted.show("成功", { type: "success" });
        });
    }
  }
};
</script>
