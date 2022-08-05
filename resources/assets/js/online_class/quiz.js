
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

// Quiz
import CreateQuiz from '../components/CreateQuiz.vue';
import ShowQuiz from '../components/ShowQuizV2.vue';
import StudentQuizResult from '../components/StudentQuizResultV2.vue';

// ONLINE CLASS QUIZ
import StartQuiz from '../components/StudentPortal/OnlineClassQuiz/StartQuiz.vue';
import StartQuizV2 from '../components/StudentPortal/OnlineClassQuiz/StartQuizV2.vue';
import ShowQuizResult from '../components/StudentPortal/OnlineClassQuiz/ShowQuizResult.vue';

import QuestionQuiz from '../components/QuestionQuiz.vue';

// Quiz
Vue.component('create-quiz', CreateQuiz);
Vue.component('show-quiz', ShowQuiz);
Vue.component('student-quiz-result', StudentQuizResult);
Vue.component('question-quiz', QuestionQuiz);
Vue.component('show-quiz-result', ShowQuizResult);

// ONLINE CLASS QUIZ
Vue.component('start-quiz', StartQuizV2);


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
