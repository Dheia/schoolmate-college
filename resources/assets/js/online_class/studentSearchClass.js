
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap');

import Vue from 'vue';
window.Vue = Vue;

// Student Search Class
import StudentSearchClass from '../components/StudentPortal/OnlineClass/StudentSearchClass.vue';

// Student Search Class
Vue.component('student-search-class', StudentSearchClass);

Vue.use(require('vue-moment'));

const app = new Vue({
	el: '#app'
});
