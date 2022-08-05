
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap');

import Vue from 'vue';
window.Vue = Vue;

window.numeral = require('numeral');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Slick from 'vue-slick'

// Online Class Posts
import ClassPostsStudent from '../components/StudentPortal/OnlineClass/ClassPosts.vue';
import ClassPostsTeacher from '../components/OnlineClass/MyClasses/TeacherClassPosts.vue';

// Single Online Post
import ClassPostsShowStudent from '../components/StudentPortal/OnlineClass/ClassPostShow.vue';

// Explore
import EmployeeExplore from '../components/OnlineClass/EmployeeExplore.vue';
import StudentExplore from '../components/StudentPortal/OnlineClass/StudentExplore.vue';

// Online Class Posts
Vue.component('class-posts-student', ClassPostsStudent);
Vue.component('class-posts-teacher', ClassPostsTeacher);

// Single Online Post
Vue.component('class-post-show-student', ClassPostsShowStudent);

// Explore
Vue.component('employee-explore', EmployeeExplore);
Vue.component('student-explore', StudentExplore);


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
