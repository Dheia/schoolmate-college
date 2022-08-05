
window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap-sass');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key',
//     cluster: 'mt1',
//     encrypted: true
// });

import Echo from 'laravel-echo'
window.Noty = require('noty');

// window.io = require('socket.io-client');

// if (  
//       location.pathname.includes("/admin/student-account/create") || 
//       location.pathname.includes("/admin/student-account")		 ||
//       location.pathname.includes("/admin/student") || 
//       location.pathname.includes("/admin/enrollment") || 
//       location.pathname.includes("/admin/encode-grade/encode")	 ||
//       location.pathname.includes("/admin/submitted-grade")   ||
//       location.pathname.includes("/admin/smartcard")   ||
//       location.pathname.includes("/library") || 
//       location.pathname.includes("/librarian") ||
//       location.pathname.includes("/student/enrollments") ||
//       location.pathname.includes("/kiosk") ||
//       location.pathname.includes("/quiz/create") ||
//       location.pathname.includes("/admin/teacher-online-class") ||
//       location.pathname.includes("/admin/quiz")  ||
//       location.pathname.includes("/admin/dashboard") ||
//       location.pathname.includes("/admin/online-class/quiz") ||
//       location.pathname.includes("/admin/online-class/student-quiz-result") ||
//       location.pathname.includes("/student/online-class-quizzes") ||
//       location.pathname.includes("/student/online-class") ||

//       // location.pathname.includes("/student") ||
//       location.pathname.includes("/student/account")
//     ) {} 
// 	else {

// 	// window.Echo = new Echo({
// 	// 	broadcaster: 'socket.io',
// 	// 	host: window.location.hostname + ':8443'
// 	// });

//     window.Pusher = require('pusher-js');

//     window.Echo = new Echo({
//         broadcaster: 'pusher',
//         key: process.env.MIX_PUSHER_APP_KEY,
//         cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//         forceTLS: true
//     });
// }

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});