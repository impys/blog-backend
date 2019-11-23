<template>
  <div class="lg:w-2/3 sm:w-full">
    <posts :posts="posts"></posts>
    <paginator :lastPage="lastPage" v-if="lastPage"></paginator>
  </div>
</template>

<script>
import Posts from "./../Post/Posts";
export default {
  components: {
    Posts
  },

  props: ["data"],

  data() {
    return {
      posts: this.data.data,
      lastPage: this.data.last_page,
      currentPage: 1
    };
  },

  mounted() {
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
            page: this.currentPage
          }
        })
        .then(res => {
          this.posts = res.data.data;
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
