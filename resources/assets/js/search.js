
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
window.Vue = Vue;

window.numeral = require('numeral');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Slick from 'vue-slick'

// Dashboard Search
import DashboardSearch from './components/Dashboard/Search.vue';
import SearchStudents from './components/SearchStudents.vue';
import SearchEnrolled from './components/SearchEnrolled.vue';

// Dashboard Search
Vue.component('dashboard-search', DashboardSearch);

Vue.component('search-students', SearchStudents);
Vue.component('search-enrolled', SearchEnrolled);

Vue.filter('formatNumber', function (value) {
	return numeral(value).format('0,0.00');
});

Vue.filter('formatNumberNoComma', function (value) {
	return numeral(value).format('0.00');
});

Vue.use(require('vue-moment'));

const app = new Vue({
	el: '#app'
});
