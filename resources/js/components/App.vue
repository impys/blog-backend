<template>
  <div class="flex">
    <header
      class="w-1/4 max-h-screen overflow-y-scroll hidden lg:block sticky top-0 text-xl text-black font-semibold h-screen"
    >
      <div class="h-12"></div>
      <div class="flex flex-col w-1/2 ml-auto">
        <router-link
          :exact="link.exact"
          :to="link.to"
          class="flex py-2"
          v-for="(link,index) in links"
          :key="index"
        >
          <div
            class="flex items-center inline-flex py-2 px-4 hover:text-blue-500 hover:bg-offwhite rounded-full"
          >
            <svg class="icon mr-4 text-2xl">
              <use :xlink:href="'#icon-'+link.iconfont" />
            </svg>
            <div>{{link.content}}</div>
          </div>
        </router-link>
      </div>
    </header>
    <keep-alive>
      <router-view v-if="$route.meta.keepAlive"></router-view>
    </keep-alive>

    <router-view v-if="!$route.meta.keepAlive"></router-view>
  </div>
</template>


<script>
export default {
  data() {
    return {
      links: [
        {
          content: "搜索",
          iconfont: "search-outline",
          to: "/search",
          exact: false
        },
        {
          content: "主页",
          iconfont: "home-outline",
          to: "/",
          exact: true
        },
        {
          content: "文章",
          iconfont: "home-outline",
          to: "/posts",
          exact: false
        }
      ]
    };
  }
};
</script>
