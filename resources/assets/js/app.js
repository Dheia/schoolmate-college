
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

// 
import RfidLogs from './components/RFIDLogs.vue';
// import RfidLogsSingle from './components/RFIDLogSingle.vue';
import GradeEncode from './components/GradeEncode.vue';
import GradeEncodeTest from './components/GradeEncodeTest.vue';
import PointOfSales from './components/PointOfSales.vue';
import StudentAccount from './components/StudentAccount.vue';
import StudentAccountSoa from './components/Student.vue';
import StudentPortal from './components/StudentPortal.vue';
import TuitionTable from './components/TuitionTable.vue';
import TuitionTableStudent from './components/TuitionTableStudent.vue';
import SubmittedGrade from './components/SubmittedGrade.vue';
import SearchBooks from './components/SearchBooks.vue';
import KioskEnrollment from './components/KioskEnrollment.vue';
import Pagination from './components/Pagination.vue';
import EmployeeRfidLogs from './components/EmployeeRfidLogs.vue';

import LibraryUser from './components/LibraryUser.vue';
import LibraryEmployee from './components/LibraryEmployee.vue';
import CanteenItem from './components/CanteenItem.vue';

// SmartCard
import SmartCard from './components/SmartCard/SmartCard.vue';
import panZoom from 'vue-panzoom';

// import SearchStudents from './components/SearchStudents.vue';
// import SearchEnrolled from './components/SearchEnrolled.vue';

// Online Class
// import SearchClass from './components/OnlineClass/MyClasses/SearchClass.vue';
// import ClassPostsStudent from './components/StudentPortal/OnlineClass/ClassPosts.vue';
// import ClassPostsTeacher from './components/OnlineClass/MyClasses/TeacherClassPosts.vue';

// Quiz
// import CreateQuiz from './components/CreateQuiz.vue';
// import ShowQuiz from './components/ShowQuiz.vue';
// import StudentQuizResult from './components/StudentQuizResult.vue';

// // Assignment
// import AssignmentList from './components/OnlineClass/Assignment/AssignmentList.vue';

// import EmployeeExplore from './components/OnlineClass/EmployeeExplore.vue';
// import StudentExplore from './components/StudentPortal/OnlineClass/StudentExplore.vue';

// // Dashboard Search
// import DashboardSearch from './components/Dashboard/Search.vue';

// ONLINE CLASS QUIZ
// import StartQuiz from './components/StudentPortal/OnlineClassQuiz/StartQuiz.vue';

// Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('rfid-logs', RfidLogs);  
// Vue.component('rfid-logs-single', RfidLogsSingle);  
Vue.component('grade-encode', GradeEncode);  
Vue.component('submitted-grade', SubmittedGrade);  
Vue.component('grade-encode-test', GradeEncodeTest);  
Vue.component('point-of-sales', PointOfSales);  
Vue.component('student-account', StudentAccount);  
Vue.component('student-account-soa', StudentAccountSoa);  
Vue.component('student-portal', StudentPortal);  
Vue.component('tuition-table', TuitionTable);  
Vue.component('tuition-table-student', TuitionTableStudent);
Vue.component('search-books', SearchBooks);
Vue.component('kiosk-enrollment', KioskEnrollment);
Vue.component('employee-rfid-logs', EmployeeRfidLogs);

Vue.component('library-user', LibraryUser);
Vue.component('library-employee', LibraryEmployee);
Vue.component('canteen-item', CanteenItem);
Vue.component('pagination', Pagination);

// Vue.component('search-students', SearchStudents);
// Vue.component('search-enrolled', SearchEnrolled);

// Online Class
// Vue.component('search-class', SearchClass);
// Vue.component('class-posts-student', ClassPostsStudent);
// Vue.component('class-posts-teacher', ClassPostsTeacher);
// Vue.component('employee-explore', EmployeeExplore);
// Vue.component('student-explore', StudentExplore);

// // Quiz
// Vue.component('create-quiz', CreateQuiz);
// Vue.component('show-quiz', ShowQuiz);
// Vue.component('student-quiz-result', StudentQuizResult);

// // Assignment
// Vue.component('assignment-list', AssignmentList);

// SmartCard
Vue.component('smart-card', SmartCard);

// // Dashboard Search
// Vue.component('dashboard-search', DashboardSearch);

// // ONLINE CLASS QUIZ
// Vue.component('start-quiz', StartQuiz);

Vue.filter('formatNumber', function (value) {
	return numeral(value).format('0,0.00');
});

Vue.filter('formatNumberNoComma', function (value) {
	return numeral(value).format('0.00');
});


import 'vue-fabric/dist/vue-fabric.min.css';
import { Fabric } from 'vue-fabric';


// import ApolloClient from "apollo-boost"
// import VueApollo from "vue-apollo"

// const apolloProvider = new VueApollo({
// 	defaultClient: new ApolloClient({
// 		uri: "/graphql"
// 	})
// });

import store from './store.js';
// import store from './quizStore.js';

// Vue.use(VueApollo);
Vue.use(Fabric);
Vue.use(panZoom);
Vue.use(require('vue-moment'));

const app = new Vue({
	el: '#app',
	store,
    // apolloProvider,
});
