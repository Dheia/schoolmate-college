
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

import Snotify from 'vue-snotify';
// You also need to import the styles. If you're using webpack's css-loader, you can do so here:
import 'vue-snotify/styles/material.css'; // or dark.css or simple.css
import 'vue-pnotify/dist/vue-pnotify.css'; // or dark.css or simple.css
// import 'bootstrap/dist/css/bootstrap.css'; // or dark.css or simple.css
// import 'font-awesome/css/font-awesome.min.css'; // or dark.css or simple.css

import VuePNotify from 'vue-pnotify'

Vue.use(Snotify);
Vue.use(VuePNotify);
Vue.use(require('vue-moment'));

 
/**
 * Employee Notification
 */
import EmployeeNotificationBell from './components/Notifications/EmployeeNotificationBell.vue';
import EmployeePushNotification from './components/Notifications/EmployeePushNotification.vue';

Vue.component('employee-notification-bell', EmployeeNotificationBell);
Vue.component('employee-push-notification', EmployeePushNotification);

/**
 * Student Notification
 */
import StudentNotificationBell from './components/Notifications/StudentNotificationBell.vue';

Vue.component('student-notification-bell', StudentNotificationBell);
 
/**
 * Parent Notification
 */
import ParentNotificationBell from './components/Notifications/ParentNotificationBell.vue';

Vue.component('parent-notification-bell', ParentNotificationBell);


 
Vue.filter('formatNumber', function (value) {
    return numeral(value).format('0,0.00');
});
 
Vue.filter('formatNumberNoComma', function (value) {
    return numeral(value).format('0.00');
});
 
 
const app = new Vue({
    el: '#app_notification_bell'
});

  // Ajax calls should always have the CSRF token attached to them, otherwise they won't work
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
 