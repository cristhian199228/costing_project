/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import vuetify from "../plugins/vuetify";
import router from "./router";
import store from "./store"
import { extend, ValidationProvider, ValidationObserver, setInteractionMode, localize} from "vee-validate";
import { required,integer, min, max, required_if } from "vee-validate/dist/rules";
import es from 'vee-validate/dist/locale/es.json'
import VuetifyMoney from "vuetify-money";


setInteractionMode("eager");
extend("required", {
    ...required,
    message: "{_field_} can not be empty"
});
extend("min", {
    ...min,
    message: "{_field_} may not be less than {length} characters"
});

extend("max", {
    ...max,
    message: "{_field_} may not be higher than {length} characters"
});

extend("integer", {
    ...integer,
    message: "{_field_} must be integer"
});
extend("required_if", {
    ...required_if,
});

localize('es')
localize({
    es
})

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('app-container', require('./components/appContainer').default);
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    vuetify,
    router,
    store,
    el: '#app',
});
