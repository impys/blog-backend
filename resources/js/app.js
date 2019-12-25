/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

require('./prism')

window.Vue = require('vue');

window.EventHub = new Vue();

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('article-card', require('./components/ArticleCard.vue').default);
Vue.component('Welcome', require('./components/Welcome/Welcome.vue').default);

// global
Vue.component('Paginator', require('./components/Global/Paginator.vue').default);
Vue.component('Search', require('./components/Global/Search/Search.vue').default);
Vue.component('Tags', require('./components/Global/Tag/Tags.vue').default);

Vue.component('Post', require('./components/Post/Post.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
