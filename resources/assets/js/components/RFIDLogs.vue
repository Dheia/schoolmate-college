<template>

    <div class="wrapper">
        <div class="container-fluid text-center">
            
            <div class="row">

                <div class="col-md-12">

                    <div class="row">
                       
                        <div class="col-md-3 mt-5">
                            
                            <div class="card" style="min-height: 364px; max-height: 364px;">
                                  <div class="card-header calendar">
                                    <p class="card-title month" v-text="currentMonth"></p>
                                  </div>
                                  <div class="card-body wrapper">
                                    
                                    <p class="card-text">
                                        <p class="dayname" v-text="currentDayName"></p>
                                        <p class="day" v-text="currentDay"></p>
                                        <p class="year" v-text="currentYear"></p>

                                    </p>
                                  </div>
                                  <div class="card-footer">
                                        <section class="section">
                                            <p class="time" v-text="currentTime"></p>
                                        </section>
                                  </div>  
                           
                            </div>
                       
                        </div>
                        <div class="col-md-6 mt-2">
                            <img :src="profilePic" class="img-logo" alt="School Logo Here" height="70">
        
                            <video width="100%" autoplay loop muted id="videoad" v-if="marketingVideo">
                              <source :src="marketingVideo" type="video/mp4">
                              Your browser does not support the video tag.
                            </video>

                            <div v-else>
                              <img :src="marketingImage" alt="Marketing Image" style="max-height:350px;">
                            </div>
                        </div>
                        <div class="col-md-3 mt-5" style="min-height: 364px; max-height: 364px;">
                            
                            <div class="card rounded-2" style="" v-if='lastLogin !== null'>
                                <div class="card-header">
                                    Last Login
                                </div>
                                <table class="table table-stripped">
                                    <tr>
                                        <td class="p-0">
                                            <div style="overflow: hidden;" class="col-md-12 m-0">
                                                <div class="image-employee" v-if="lastLogin">
                                                    <img v-bind:src="lastLogin.image" style="width:60px;" alt="" class="card-img-top rounded-circle employee-small">
                                                </div>
                                                    
                                            </div>
                                            <div class="col-md-12">
                                                <p class="m-0">
                                                    {{lastLogin.firstname}} {{lastLogin.lastname}}
                                                </p>
                                                <div class="timeinfo">
                                                    <span class="timein">Time in: <b>{{lastLogin.timein}} </b></span>
                                                    <span class="timeout">Time Out: <b> {{lastLogin.timeout}} </b></span>
                                                </div>


                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-for="(employee, index) in topSixEmployees" v-if="employee !== null"> 
                                        <td align="left">
                                            <div class="image-employee" v-if="employee">
                                                    <img v-bind:src="employee.image" style="width:30px;" alt="" class="card-img-top rounded-circle employee-many">
                                                    {{employee.firstname}} {{employee.lastname}}
                                                    <i class="fa fa-clock"></i> <div class="timeinfo2">
                                                        <span class="badge badge-info">{{employee.timein}}</span>
                                                    </div>
                                                    <!-- Check -->
                                            </div>
                                            

                                        </td>
                                    </tr>
                                </table>
                            
                            </div>
                        </div>    
                        
                    </div>
                </div>

                <div class="col-md-1">
                    
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row mt-2">
                <div class="col-md-10 offset-md-1">
                    <div class="row">
                        <!-- {{ topSixStudents }} -->
                        <div class="col-md-2 text-center" v-for="(student, index) in topSixStudents" v-if="student !== null">
                                
                            <!-- {{ student[0]}} -->
                            <div style="overflow: hidden;">
                            <div class="image-student" v-if="student">
                                <img v-bind:src="student.image" alt="" class="card-img-top rounded-circle" >
                            </div>
                                
                                
                            </div>
                             <div class="badge badge-success mb-1">{{ student.current_level }}</div>
                                    
                            <div class="name">

                                <h5 class="card-title" v-if="student.firstname != null && student.lastname !== null"> {{student.firstname}} {{student.lastname}}</h5>
                                <div class="card-text"><i class="fa fa fa-clock-o"></i>{{student.timein}}</div>
                                
                            </div>
                              
                        </div>
                    </div>
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
        'image'
    ],
    data() {
        return {
            
            // infos: [],
            topSixStudents: [],
            topSixEmployees: [],
            lastLogin: [],
            fullName: null,
            rfid: '',
            profilePic: this.logo,
            marketingVideo: this.video,
            marketingImage: this.image,

            slickOptions: {
                slidesToShow: 4,
                infinite: true,
                accessibility: true,
                adaptiveHeight: false,
                arrows: true,
                dots: true,
                draggable: true,
                edgeFriction: 0.30,
                swipe: true
            },
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

        setInterval(() => this.updateCurrentDayName(), 1 * 1000);
        setInterval(() => this.updateCurrentDay(), 1 * 1000);
        setInterval(() => this.updateCurrentMonth(), 1 * 1000);
        setInterval(() => this.updateCurrentTime(), 1 * 1000);
        setInterval(() => this.updateCurrentDate(), 1 * 1000);
        setInterval(() => this.updateCurrentYear(), 1 * 1000);

        Echo.channel('display-channel')

        .listen('DisplayLastLogin', (data) => {
            let dataStudent = _.values(data.student);
            let dataEmployee = _.values(data.employee);
            let dataLastLogin = dataLastLogin = data.lastlogin;;
            // if(data.lastlogin){
            //     $('.not-allowed').css('display','none');
            //      dataLastLogin = data.lastlogin;

            // }else {
            //     dataLastLogin = [];
            //     $('.not-allowed').css('display','block');
            // }
            
        

            var arrayStudents = [];
            var arrayEmployees = [];

            let lastCount = 0;

            _.forEach(dataStudent, function(value, key) {
                if(value){
                    if(value.is_student == true){
                    // console.log(value);
                        arrayStudents.push(value);
                    }
                }
            });

             _.forEach(dataEmployee, function(value, key) {
                if(lastCount <= 3){   
                    if(value){
                        if(value.is_student == false){
                        // console.log(value);
                            arrayEmployees.push(value);
                        }
                    }
                    lastCount = lastCount + 1;
                }
            });

            this.topSixEmployees =  arrayEmployees;
            this.topSixStudents =  arrayStudents;
            this.lastLogin = dataLastLogin;

            


        }

        );

    }
}

</script>


<style>
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

</style>

