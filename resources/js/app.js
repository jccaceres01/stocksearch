/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('output-index-component', require('./components/outputs/OutputIndex.vue').default);
Vue.component('output-component', require('./components/outputs/Output.vue').default);
Vue.component('outputslines-component', require('./components/outputs/OutputLines.vue').default);
Vue.component('new-output-component', require('./components/outputs/NewOutput.vue').default);
Vue.component('loading-component', require('./components/Loading.vue').default);
Vue.component('errors-component', require('./components/Errors.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Import Vuex store from store/index
import Store from './store'

const app = new Vue({
    el: '#app',
    store: Store
});
