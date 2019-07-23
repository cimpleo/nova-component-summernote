Nova.booting((Vue, router) => {
  Vue.component('index-nova-summernote', require('./components/IndexField'));
  Vue.component('detail-nova-summernote', require('./components/DetailField'));
  Vue.component('form-nova-summernote', require('./components/FormField'));
});
