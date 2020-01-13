require('./bootstrap');

require('./prism')

require('./directives')

import * as helper from './helper'
window.helper = helper

window.Vue = require('vue');

window.EventHub = new Vue();

Vue.component('App', require('./components/App.vue').default);
Vue.component('MainLayout', require('./components/Layout/MainLayout.vue').default);
Vue.component('Tags', require('./components/Share/Tags.vue').default);
Vue.component('PostCard', require('./components/Share/PostCard.vue').default);

import VueRouter from 'vue-router'
Vue.use(VueRouter)


//see https://github.com/vuejs/vue-router/issues/2872
const originalPush = VueRouter.prototype.push
VueRouter.prototype.push = function push(location) {
    return originalPush.call(this, location).catch(err => err)
}

import routes from './routes'
const router = new VueRouter({
    routes,
    mode: 'history',
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { x: 0, y: 0 }
        }
    }
})

router.beforeEach((to, from, next) => {
    const CancelToken = axios.CancelToken
    store.source.cancel && store.source.cancel()
    store.source = CancelToken.source()
    next()
})

const app = new Vue({
    el: '#app',
    router
});


