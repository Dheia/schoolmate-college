<template>

    <div class="container-fluid p-0 m-0" style="height: 600px;">

        <div class="row m-0">

            <!-- COLUMN 1 -->
            <div class="col-md-3 p-0 new-tap">
                <div class="col-md-10 mx-auto">
                    <div class="card profile-card-3 mx-auto shadow">
                        <div class="background-block">
                            <img src="https://images.pexels.com/photos/459225/pexels-photo-459225.jpeg?auto=compress&cs=tinysrgb&h=650&w=940" alt="profile-sample1" class="background"/>
                        </div>
                        <div class="profile-thumb-block">
                            <img v-if="lastLogin !== null" :src="lastLogin.data.photo" alt="profile-image" class="profile"/>
                            <img v-if="lastLogin == null" class="profile" :src="'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQyQSLHp_LlY_YuT9yqXwvdqNdz3l81s2uYjwDnjDxK9wJHw-90&s'" alt="">
                        </div>
                        <div class="card-content">
                            <h4 class="mb-0 full-name" v-if="lastLogin !== null"><b>{{ lastLogin.data.full_name }}</b><br></h4>
                            <p class="mb-0 position" v-if="lastLogin !== null">{{ lastLogin.data.position }}</p>
                            <hr class="mb-0">
                            <div class="icon-block">
                                <h1 v-if="lastLogin !== null" class="text-center mb-0 tap-time">{{ lastLogin.tap.time }}</h1>
                                <h2 v-if="lastLogin !== null" class="text-center text-uppercase tap-type"><b><span style="color: black; text-shadow: 1px 1px 1px #000;">{{ lastLogin.tap.type }}</span></b></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- COLUMN 2 -->
            <div class="col-md-6 p-0 employee-logs-wrapper">
                <div class="bg-white card profile-card-3 p-2 shadow employee-logs-wrapper">
                    <div class="profile-card-3 p-0 d-flex flex-column " style="overflow: hidden; background-color: rgb(249, 246, 246);">
                        
                        <!-- EMPLOYEE IN -->
                        <div class="employee-in-wrapper border-bottom d-flex" style="height: 50%; overflow: hidden;">

                            <div class="employee-in-sidebar" style="background: #fde29d; height: 100%; text-orientation: upright; writing-mode: vertical-lr;">
                                <b>IN</b>
                            </div>

                            <div class="employee-in-logs d-flex flex-row flex-fill">

                                <div v-if="employeeLogs.in.length > 0" v-for="employee in employeeLogs.in" class="col-md-3 p-1 mt-3 fadeInRight align-self-center">
                                    <div class="profile-picture">
                                        <img :src="employee.data.photo" alt="" class="rounded-circle" style="max-width: 100px;">
                                    </div>
                                    <p class="mt-1 mb-0 badge badge-info">{{ employee.data.firstname }}</p>
                                    <p><b>{{ employee.tap.time }}</b></p>
                                </div>

                            </div>
                        </div>
                        

                        <!-- EMPLOYEE OUT -->
                        <div class="employee-out-wrapper d-flex" style="height: 50%; overflow: hidden;">

                            <div class="employee-in-sidebar d-flex order-2 flex-column" style="background: rgb(255, 47, 47); height: 100%; text-orientation: upright; writing-mode: vertical-lr; color: #FFF;">
                                <b>OUT</b>
                            </div>

                            <div class="employee-out-logs d-flex order-1 justify-content-end flex-row flex-fill">

                                <div v-if="employeeLogs.out.length > 0" v-for="employee in employeeLogs.out" class="col-md-3 p-1 mt-3 fadeInLeft align-self-center">
                                    <div class="profile-picture">
                                        <img :src="employee.data.photo" alt="" class="rounded-circle" style="max-width: 100px;">
                                    </div>
                                    <p class="mt-1 mb-0 badge badge-info">{{ employee.data.firstname }}</p>
                                    <p><b>{{ employee.tap.time }}</b></p>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- COLUMN 3 -->
            <div class="col-md-3">
                <div class="col-md-10 p-0 mx-auto">
                    <div class="card" style="min-height: 364px; max-height: 364px;">
                        <div class="card-header calendar">
                            <p class="card-title month text-center" v-text="currentMonth"></p>
                        </div>

                        <div class="card-body wrapper">
                            <p class="card-text">
                                <p class="dayname text-center" v-text="currentDayName"></p>
                                <p class="day text-center" v-text="currentDay"></p>
                                <p class="year text-center" v-text="currentYear"></p>
                            </p>
                        </div>

                        <div class="card-footer">
                            <section class="section clock">
                                
                                <!-- <div class="clock"> -->
                                    <!-- <div id="Date"></div> -->
                                    <ul>
                                        <li id="hours"></li>
                                        <li id="point">:</li>
                                        <li id="min"></li>
                                        <li id="point">:</li>
                                        <li id="sec"></li>
                                    </ul>
                                <!-- </div> -->
                            </section>
                        </div>  
                    </div>
                </div>
            </div>

        </div> <!-- ,row -->
        
        <div class="row m-0" style="height: calc(100% - 200px);">
            <div class="col-md-9 p-0 student-logs" style="backround:color: red;">
                <div class="col-md-12 mx-auto pt-5 pr-0 pl-5">
                        <div class="bg-white card p-2 shadow student-logs-wrapper">
                            <div class="d-flex flex-row flex-wrap p-0 " style="background-color: rgb(249, 246, 246);">

                                <div v-if="studentLogs.length > 0" v-for="student in studentLogs" class="col-md-3 p-1 mt-3 fadeInLeft align-self-center">
                                    <div class="profile-picture">
                                        <img :src="'storage/' + student.data.photo" alt="" class="d-block mx-auto rounded-circle" style="max-width: 150px;">
                                    </div>
                                    <p class="mt-1 mb-0 text-capitalize text-center fullname">{{ student.data.full_name }}</p>
                                    <p class="text-center tap-timer"><b>{{ student.tap.time }}</b></p>
                                </div>

                            </div>
                        </div>
                </div>
            </div>
            <div class="col-md-3 p-0">
                <div class="col-md-10 py-5 mx-auto school-info">
                    <img :src="logo" class="d-block mx-auto img-fluid" alt="School Logo" style="height: 200px">
                    <h3 class="text-center mt-4 school-name">{{ schoolname }}</h3>
                    <p class="text-center school-address">{{ schooladdress }}</p>
                </div>
            </div>
        </div>


    </div>
    


</template>


<script>

export default {
    props: [
        'msg',
        'video',
        'logo',
        'image',
        'schoolname',
        'schooladdress'
    ],
    data() {
        return {
            
            // infos: [],
            lastLogin: null,
            studentLastLogin: [],
            employeeLogs: {
                in: [],
                out: [],
            },
            studentLogs: [],

            currentDate: null,
            currentTime: null,
            currentDayName: null,
            currentDay: null,
            currentYear: null,
            currentMonth: null
        }
    },
    methods : {
        
        updateCurrentTime() {
          this.currentTime = moment().format('LTS');
        },
        updateCurrentDate() {
          this.currentDate = moment().format('MMM D YYYY');
        },
        updateCurrentMonth() {
            this.currentMonth = moment().format('MMMM');
        },
        updateCurrentDay() {
            this.currentDay = moment().format('D');
        },
        updateCurrentDayName() {
            this.currentDayName = moment().format('dddd');
        },
        updateCurrentYear() {
            this.currentYear = moment().format('YYYY');
        },
        separateStudent() {
            // let data = this.rawData
            _.forEach(this.rawData, function(value, key) {
                // console.log(key);
            });
        }
    },
    created() {


        // Create two variables with names of months and days of the week in the array
        var monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ]; 
        var dayNames= ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]

        // Create an object newDate()
        var newDate = new Date();
        // Retrieve the current date from the Date object
        newDate.setDate(newDate.getDate());
        // At the output of the day, date, month and year    
        $('#Date').html(dayNames[newDate.getDay()] + " " + newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());

        setInterval( function() {
            // Create an object newDate () and extract the second of the current time
            var seconds = new Date().getSeconds();
            // Add a leading zero to the value of seconds
            $("#sec").html(( seconds < 10 ? "0" : "" ) + seconds);
            },1000);
            
        setInterval( function() {
            // Create an object newDate () and extract the minutes of the current time
            var minutes = new Date().getMinutes();
            // Add a leading zero to the minutes
            $("#min").html(( minutes < 10 ? "0" : "" ) + minutes);
            },1000);
            
        setInterval( function() {
            // Create an object newDate () and extract the clock from the current time
            var hours = new Date().getHours();
            // Add a leading zero to the value of hours
            $("#hours").html(( hours < 10 ? "0" : "" ) + hours);
            }, 1000);

        setInterval(() => this.updateCurrentDayName(), 1 * 1000);
        setInterval(() => this.updateCurrentDay(), 1 * 1000);
        setInterval(() => this.updateCurrentMonth(), 1 * 1000);
        // setInterval(() => this.updateCurrentTime(), 1 * 1000);
        setInterval(() => this.updateCurrentDate(), 1 * 1000);
        setInterval(() => this.updateCurrentYear(), 1 * 1000);

        Echo.channel('employee-channel')
            .listen('EmployeeDisplayLastLogin', (response) => {
                this.lastLogin = response;

                if(this.lastLogin.tap.type === "in") {
                    if(this.employeeLogs.in.length == 4) {
                        this.employeeLogs.in.shift();
                        this.employeeLogs.in.push(response);
                    } else {
                        this.employeeLogs.in.push(response);
                    }
                }

                if(this.lastLogin.tap.type === "out") {
                    if(this.employeeLogs.out.length == 4) {
                        this.employeeLogs.out.pop();
                        this.employeeLogs.out.unshift(response);
                    } else {
                        this.employeeLogs.out.unshift(response);
                    }
                }
            });

        Echo.channel('student-channel')
            .listen('StudentDisplayLastLogin', (response) => {
                if(this.studentLogs.length === 4) {
                    this.studentLogs.shift();
                    this.studentLogs.push(response);
                } else {
                    this.studentLogs.push(response);
                }
            }
        );

    }
}

</script>


<style>


    #Date {
      font-family: Arial, Helvetica, sans-serif;
      font-size:36px;
      text-align:center;
      text-shadow:0 0 5px #00c6ff;
    }

    .clock ul {
      margin:0 auto;
      padding:0px;
      list-style:none;
      text-align:center;
    }

    .clock ul li {
      display:inline;
      font-size:3em;
      text-align:center;
      font-family:Arial, Helvetica, sans-serif;
      text-shadow:0 0 5px #00c6ff;
    }

    #point {
      position:relative;
      -moz-animation:mymove 1s ease infinite;
      -webkit-animation:mymove 1s ease infinite;
      padding-left:10px; padding-right:10px;
    }

    @-webkit-keyframes mymove 
    {
    0% {opacity:1.0; text-shadow:0 0 20px #00c6ff;}
    50% {opacity:0; text-shadow:none; }
    100% {opacity:1.0; text-shadow:0 0 20px #00c6ff; }    
    }

    @-moz-keyframes mymove 
    {
    0% {opacity:1.0; text-shadow:0 0 20px #00c6ff;}
    50% {opacity:0; text-shadow:none; }
    100% {opacity:1.0; text-shadow:0 0 20px #00c6ff; }    
    }
    .not-allowed {
        opacity:.9;
        background-color:#ccc;
        position:fixed;
        width:100%;
        height:100%;
        top:0px;
        left:0px;
        z-index:1000;
        text-align: center;
        font-size: 90px;
        padding-top: 50px;
        display: none;
    }
    .warning-message {
        opacity:1;
    }
    .warning-message p.title{
        color: red;
    }
    .warning-message small.sub {
        font-size: 50px;
        z-index:1001;
        
    }
    .employee-small {
        width: 30px;
        border: 2px solid #244b7d;
    }
    .employee-many {
        width: 30px;
        border: 2px solid #244b7d;
    }
    .announcements {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 40px;
        /*background-color: red;*/
        color: white;
        
        font-size: 25px;
        
    }
    .announcements .title{
        position: absolute;
        background-color: blue;
        left:0px;
        height: 40px;
        padding-right: 5px;
    }
    .announcements .marquee {
      
        height: 45px;
        width: 100%;
        overflow: hidden;
        position: relative;
        
    }
    .timeinfo2 {
        display: inline;
        float: right;
        margin-top: 2px;
        font-size: 12px;
    }
    

    section.section {
     
      align-items: center;
      
      background: transparent;
    }

    h3.is-3, p.time {
      color: black;
    }

    h3.is-3:not(:last-child) {
      margin: 0;
      padding: 0;
    }

    .time {
      font-size: 30px;
    }

    .image-student img{
        border: 3px solid #244b7d;
    }

    .shadow {
      /*text-shadow: 0 0 15px rgba(100, 100, 100, .35);*/
    }
    .calendar {
        /* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#1e5799+0,2989d8+50,207cca+51,7db9e8+100;Blue+Gloss+Default */
        background: rgb(30,87,153); /* Old browsers */
        background: -moz-linear-gradient(top,  rgba(30,87,153,1) 0%, rgba(41,137,216,1) 50%, rgba(32,124,202,1) 51%, rgba(125,185,232,1) 100%); /* FF3.6-15 */
        background: -webkit-linear-gradient(top,  rgba(30,87,153,1) 0%,rgba(41,137,216,1) 50%,rgba(32,124,202,1) 51%,rgba(125,185,232,1) 100%); /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(to bottom,  rgba(30,87,153,1) 0%,rgba(41,137,216,1) 50%,rgba(32,124,202,1) 51%,rgba(125,185,232,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#1e5799', endColorstr='#7db9e8',GradientType=0 ); /* IE6-9 */
    }
    .month {
        font-size: 30px;
        color: white;
        
        
    }
    .card-header {
        padding: 0px;
        padding-top: 10px;
    }
    .card-body.wrapper {
        background-color: #f2f2f2;
        padding: 0px;
    }
    .dayname {
        font-size: 28px;
        padding: 0px;
        margin:0px;

    }
    .day {
        font-size: 65px;
        padding: 0px;
        margin:0px;
        font-weight: bolder;
    }
    .year {
        font-size: 25px;
        padding: 0px;
        margin:0px;
    }
    .ict-logo {
        width: 100px;
        height: 100px;
        background-image: url();
    }
    .timeinfo {
        font-size: 14px;
    }

    /*Profile Card 3*/
    .profile-card-3 {
      font-family: 'Open Sans', Arial, sans-serif;
      position: relative;
      float: left;
      overflow: hidden;
      width: 100%;
      text-align: center;
      height:428px;
      border:none;
    }
    .profile-card-3 .background-block {
        float: left;
        width: 100%;
        height: 214px;
        overflow: hidden;
    }
    .profile-card-3 .background-block .background {
      width:100%;
      vertical-align: top;
      opacity: 0.9;
      -webkit-filter: blur(0.5px);
      filter: blur(0.5px);
       -webkit-transform: scale(1.8);
      transform: scale(2.8);
    }
    .profile-card-3 .card-content {
      width: 100%;
      padding: 15px 25px;
      color:#232323;
      float:left;
      background:#efefef;
      height:50%;
      border-radius:0 0 5px 5px;
      position: relative;
      z-index: 9999;
    }
    .profile-card-3 .card-content::before {
        content: '';
        background: #efefef;
        width: 120%;
        height: 100%;
        left: 11px;
        bottom: 51px;
        position: absolute;
        z-index: -1;
        transform: rotate(-13deg);
    }
    .profile-card-3 .profile {
      border-radius: 50%;
      position: absolute;
      bottom: 50%;
      left: 50%;
      max-width: 125px;
      opacity: 1;
      box-shadow: 3px 3px 20px rgba(0, 0, 0, 0.5);
      border: 2px solid rgba(255, 255, 255, 1);
      -webkit-transform: translate(-50%, 0%);
      transform: translate(-50%, 0%);
      z-index:99999;
    }
    .profile-card-3 h2 {
      margin: 0 0 5px;
      font-weight: 600;
      font-size:25px;
    }
    .profile-card-3 h2 small {
      display: block;
      font-size: 15px;
      margin-top:10px;
    }
    .profile-card-3 i {
      display: inline-block;
        font-size: 16px;
        color: #232323;
        text-align: center;
        border: 1px solid #232323;
        width: 30px;
        height: 30px;
        line-height: 30px;
        border-radius: 50%;
        margin:0 5px;
    }
    .profile-card-3 .icon-block{
        float:left;
        width:100%;
        margin-top:15px;
    }
    .profile-card-3 .icon-block a{
        text-decoration:none;
    }
    .profile-card-3 i:hover {
      background-color:#232323;
      color:#fff;
      text-decoration:none;
    }
</style>

