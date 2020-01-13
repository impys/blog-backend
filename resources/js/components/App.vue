<template>
  <div class="flex">
    <header
      class="w-1/4 hidden lg:block max-h-screen overflow-y-scroll hidden lg:block sticky top-0 text-xl font-semibold h-screen"
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

    <header
      class="flex flex-row block lg:hidden border-t-.5 border-grey py-1 px-4 justify-between fixed w-full bg-offwhite bottom-0 text-xl text-grey test"
    >
      <router-link
        :exact="link.exact"
        :to="link.to"
        class="flex flex-col items-center justify-center"
        v-for="(link,index) in links"
        :key="index"
      >
        <svg class="icon text-xl">
          <use :xlink:href="'#icon-'+link.iconfont" />
        </svg>
        <div class="text-xs">{{link.content}}</div>
      </router-link>
    </header>
  </div>
</template>


<script>
export default {
  data() {
    return {
      links: [
        // {
        //   content: "主页",
        //   iconfont: "home-outline",
        //   to: "/",
        //   exact: true
        // },
        {
          content: "文章",
          iconfont: "post-outline",
          to: "/posts",
          exact: false
        },
        {
          content: "搜索",
          iconfont: "search-outline",
          to: "/search",
          exact: false
        },
        {
          content: "标签",
          iconfont: "tag-outline",
          to: "/tags",
          exact: true
        }
      ]
    };
  }
};
</script>
