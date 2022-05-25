
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('asset-manager', require('./components/AssetManager.vue'));
Vue.component('asset-editor', require('./components/AssetEditor.vue'));
Vue.component('asset-collection', require('./components/AssetCollection.vue'));
Vue.component('panel', require('./components/Panel.vue'));
Vue.component('dropzone-panel', require('./components/DropzonePanel.vue'));
Vue.component('user-removal-modal', require('./components/UserRemovalModal.vue'));

Vue.config.productionTip = false;

const app = new Vue({
    el: '#app'
});

window.app = app;