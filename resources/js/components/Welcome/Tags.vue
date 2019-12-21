<template>
  <div class="flex flex-wrap -mx-1 justify-between customer__tags">
    <div
      class="inline-block mx-1 mb-2 border px-2 py-1 rounded text-sm cursor-pointer"
      v-bind:class="[ isSelected(tag) ? 'bg-primary':'',isSelected(tag) ? 'text-white':'',isSelected(tag) ? 'border-primary':'']"
      v-for="(tag,index) in tags"
      :key="index"
      v-on:click="clickTag(tag)"
    >
      <span>{{tag.name}}</span>
      <span>{{tag.posts_count}}</span>
    </div>
  </div>
</template>

<script>
export default {
  props: ["tags"],

  data() {
    return {
      selectedTags: []
    };
  },

  computed: {
    selectedTagIds: function() {
      return this.selectedTags.map(tag => {
        return tag.id;
      });
    }
  },

  methods: {
    isSelected: function(tag) {
      return this.selectedTags.indexOf(tag) != -1;
    },

    clickTag(tag) {
      let index = this.selectedTags.indexOf(tag);
      if (index == -1) {
        this.selectedTags.push(tag);
      } else {
        this.selectedTags.splice(index, 1);
      }
      EventHub.$emit("clickTag", this.selectedTagIds);
    }
  }
};
</script>
