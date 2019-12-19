<template>
  <div class="lg:w-2/3 sm:w-full">
    <post-cards :posts="posts"></post-cards>
    <paginator :lastPage="lastPage" v-if="lastPage > 1"></paginator>
  </div>
</template>

<script>
import PostCards from "./Cards/PostCards";
export default {
  components: {
    PostCards
  },

  props: ["data"],

  data() {
    return {
      posts: this.data.posts.data,
      lastPage: this.data.posts.last_page,
      currentPage: 1,
      tagIds: []
    };
  },

  mounted() {
    EventHub.$on("clickPaginationButton", currentPage => {
      this.handleClickPaginationButton(currentPage);
    });
    EventHub.$on("clickTag", selectedTagIds => {
      this.handleCilckTag(selectedTagIds);
    });
  },

  watch: {
    currentPage: function(newValue, oldValue) {
      EventHub.$emit("updatePaginatorCurrentPage", this.currentPage);
    }
  },

  methods: {
    fetchPost() {
      axios
        .get("/posts", {
          params: {
            page: this.currentPage,
            tags: JSON.stringify(this.tagIds)
          }
        })
        .then(res => {
          this.posts = res.data.data;
          this.lastPage = res.data.meta.last_page;
          this.scrollToTop();
        })
        .catch(e => {});
    },

    handleClickPaginationButton(currentPage) {
      this.currentPage = currentPage;
      this.fetchPost();
    },

    handleCilckTag(selectedTagIds) {
      this.tagIds = selectedTagIds;
      this.currentPage = 1;
      this.fetchPost();
    },

    scrollToTop() {
      window.scrollTo({
        top: 0,
        behavior: "smooth"
      });
    }
  }
};
</script>
