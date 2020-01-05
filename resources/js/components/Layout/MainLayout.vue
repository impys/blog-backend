<template>
  <div class="flex w-full lg:w-3/4">
    <div class="w-full lg:w-2/3">
      <div class="flex items-center font-semibold h-12 text-xl sticky top-0 bg-white z-20">
        <slot name="header"></slot>
      </div>
      <slot name="content"></slot>
    </div>
    <div class="w-1/3 pl-8 hidden lg:block">
      <div class="flex items-start py-1 font-semibold h-12 text-xl sticky top-0 bg-white z-20">
        <slot name="sidebar-header">
          <simple-search></simple-search>
        </slot>
      </div>
      <slot name="sidebar-content"></slot>
    </div>
  </div>
</template>
