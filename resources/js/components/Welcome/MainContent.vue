<template>
  <div class="lg:w-2/3 sm:w-full">
    <posts :posts="posts"></posts>
    <paginator :lastPage="meta.last_page" v-if="meta"></paginator>
  </div>
</template>

<script>
import Posts from "./../Post/Posts";
export default {
  components: {
    Posts
  },

  data() {
    return {
      posts: null,
      meta: null,
      currentPage: 1,
      size: 15
    };
  },

  mounted() {
    this.fetchPost();
    EventHub.$on("updateCurrentPage", currentPage => {
      this.scrollToTop();
      this.updateCurrentPage(currentPage);
    });
  },

  watch: {
    currentPage: function(newValue, oldValue) {
      this.fetchPost();
    }
  },

  methods: {
    fetchPost() {
      axios
        .get("/posts", {
          params: {
            page: this.currentPage,
            size: this.size
          }
        })
        .then(res => {
          this.posts = res.data.data;
          this.meta = res.data.meta;
        })
        .catch(e => {});
    },
    updateCurrentPage(currentPage) {
      this.currentPage = currentPage;
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
