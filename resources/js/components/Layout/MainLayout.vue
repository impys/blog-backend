<template>
  <div class="flex w-full lg:w-3/4">
    <div class="w-full lg:w-2/3">
      <div
        class="flex items-center justify-center h-12 sticky top-0 font-semibold text-black bg-white z-20 px-4 py-2 text-base lg:text-xl"
      >
        <slot name="header"></slot>
      </div>
      <div class="mb-16 px-4">
        <slot name="content"></slot>
      </div>
    </div>
    <div class="w-1/3 pl-8 hidden lg:block">
      <div
        class="flex items-center font-semibold h-12 sticky top-0 font-semibold text-black bg-white z-20 px-4 py-2 text-xl"
      >
        <slot name="sidebar-header">
          <!-- <simple-search></simple-search> -->
        </slot>
      </div>
      <slot name="sidebar-content"></slot>
    </div>
  </div>
</template>
