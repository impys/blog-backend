Nova.booting((Vue, router, store) => {
  Vue.component('index-post-editor', require('./components/IndexField'))
  Vue.component('detail-post-editor', require('./components/DetailField'))
  Vue.component('form-post-editor', require('./components/FormField'))
})
