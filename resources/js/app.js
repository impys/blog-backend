require('./bootstrap');

require('./prism')

require('./directives')

window.Vue = require('vue');

window.EventHub = new Vue();

Vue.component('App', require('./components/App.vue').default);
Vue.component('MainLayout', require('./components/Layout/MainLayout.vue').default);
Vue.component('Tags', require('./components/Share/Tags.vue').default);
Vue.component('SimpleSearch', require('./components/SimpleSearch/SimpleSearch.vue').default);

import VueRouter from 'vue-router'
Vue.use(VueRouter)

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

const app = new Vue({
    el: '#app',
    router
});


