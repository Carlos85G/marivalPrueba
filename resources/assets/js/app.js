
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

import { library } from '@fortawesome/fontawesome-svg-core';
import { faCoffee } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faCoffee);

Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('usuarios-lista', require('./components/UsuariosLista.vue'));
Vue.component('usuarios-usuario', require('./components/UsuariosUsuario.vue'));
Vue.component('usuario-editor', require('./components/UsuarioEditor.vue'));

const app = new Vue({
    el: '#app'
});
