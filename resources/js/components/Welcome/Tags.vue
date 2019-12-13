<template>
  <div>
    <div
      class="inline-block mr-2 mb-1 border px-1 rounded text-xs cursor-pointer"
      v-bind:class="[ isSelected(tag) ? 'bg-red-400':'',isSelected(tag) ? 'text-white':'',isSelected(tag) ? 'border-red-400':'']"
      v-for="(tag,index) in tags"
      :key="index"
      v-on:click="clickTag(tag)"
    >{{tag.name+' '+tag.posts_count}}</div>
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
