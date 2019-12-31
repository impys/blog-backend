<template>
  <div id="toc">
    <div class="mb-2 text-ching text-lg">目录</div>
    <ul v-for="(toc,index) in tocsTree" :key="index" class="text-grey">
      <li
        @click="handleCilckToc"
        class="cursor-pointer hover:text-ching mb-2 font-medium"
        :id="'toc-'+toc.value"
      >{{ toc.value }}</li>
      <li v-if="toc.children">
        <ul v-for="(childToc,childIndex) in toc.children" :key="childIndex" class="ml-3">
          <li
            @click="handleCilckToc"
            class="cursor-pointer hover:text-ching mb-2 text-sm"
            :id="'toc-'+childToc.value"
          >{{ childToc.value }}</li>
        </ul>
      </li>
    </ul>
  </div>
</template>
<script>
export default {
  props: ["tocs"],

  computed: {
    tocsTree: function() {
      const tocs = [];
      for (const key in this.tocs) {
        const toc = this.tocs[key];
        if (toc.level == 2) {
          toc.children = [];
          tocs.push(toc);
        }
        if (toc.level == 3) {
          if (
            tocs[tocs.length - 1] &&
            tocs[tocs.length - 1].hasOwnProperty("children")
          ) {
            tocs[tocs.length - 1].children.push(toc);
          }
        }
      }
      return tocs;
    }
  },
  methods: {
    handleCilckToc(e) {
      let id = e.srcElement.innerText;
      document.querySelector("#" + id).scrollIntoView({
        block: "start",
        behavior: "auto"
      });
    }
  }
};
</script>

<style lang="scss">
#toc {
  counter-reset: chapter;
  ul {
    li:not(:last-child) {
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      counter-reset: section;
      &::before {
        counter-increment: chapter;
        content: counter(chapter) " ";
        padding-left: 2px;
      }
    }
    li {
      ul {
        li {
          overflow: hidden;
          text-overflow: ellipsis;
          white-space: nowrap;
          &::before {
            counter-increment: section;
            content: counter(chapter) "." counter(section) " ";
          }
        }
      }
    }
  }
}
</style>
