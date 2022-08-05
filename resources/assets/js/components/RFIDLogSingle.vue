<template>
<div class="wrapper">
    <div class="container-fluid text-center">
        
        <div class="row">

            <div class="col-md-12 col-xs-12 col-sm-12">
                <center>
                    <img src="/images/WIS_LOGO.png" alt="SchoolMATE Logo" height="90px">
                </center>

                <div class="row">
                   
                    <div class="col-md-9 col-xs-8 col-sm-8 m-0 mt-1">
                        
                        <div class="card rounded-2" style="" v-if='lastLogin !== null' style="min-height: 364px; max-height: 364px;">
                            <div class="card-header calendar">
                                <p class="card-title month">Last Login</p>
                              </div>
                            <table class="table table-stripped">
                                <tr>
                                    <td class="p-0">
                                        <div style="overflow: hidden;" class="col-md-12 m-2">
                                            <div class="image-employee" v-if="latestStudent">
                                                <img v-bind:src="latestStudent.image" style="width:200px; border: 2px solid rgb(158, 63, 176);" alt="" class="card-img-top rounded-circle">
                                            </div>
                                                
                                        </div>
                                        <div class="col-md-12">
                                            <p class="m-0 studentname">
                                                {{latestStudent.firstname}} {{latestStudent.lastname}}
                                            </p>
                                            <div class="timeinfo">
                                                <span class="timein">Time in: <b>{{latestStudent.timein}} </b></span>
                                                <span class="timeout">Time Out: <b> {{latestStudent.timeout}} </b></span>
                                            </div>


                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="(employee, index) in topSixEmployees" v-if="employee !== null">
                                    <td>
                                        
                                        {{employee.firstname}} {{employee.lastname}}

                                    </td>
                                </tr>
                            </table>
                        
                        </div>
                    </div> 
                    <div class="col-md-3 col-xs-4 col-sm-4 mt-1">
                        
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
                    
                </div>
            </div>

            <div class="col-md-1">
                
            </div>
        </div>
    

    </div>
   
    <div class="announcements">
        <span class="title">Announcements:</span>
        <div class="marquee">
          <div>
            <span>Welcome to Westfields! Have a great and fun day!</span>
            <span>Receive SMS Notifications! Visit the admissions office!</span>
          </div>
        </div>
    </div>
</div>

</template>

<style>
    .studentname {
        font-size: 25px;
    }
    .announcements {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 40px;
        background-color: red;
        color: white;
        
        font-size: 25px;
        padding-left: 200px;
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

    .announcements .marquee div {
        display: block;
        width: 200%;
        height: 30px;

        position: absolute;
        overflow: hidden;

        animation: marquee 5s linear infinite;
    }

    .announcements .marquee span {
        float: left;
        width: 50%;
    }

    @keyframes marquee {
      0% { 
        left: 0;
        animation-duration: 10s;
        }

      100% {
        animation-duration: 10s;
        left: -100%; 
        }
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

    .shadow {
      /*text-shadow: 0 0 15px rgba(100, 100, 100, .35);*/
    }
    .calendar {
        background: #cb60b3; /* Old browsers */
background: -moz-linear-gradient(top, #cb60b3 0%, #c146a1 50%, #a80077 51%, #db36a4 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(top, #cb60b3 0%,#c146a1 50%,#a80077 51%,#db36a4 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom, #cb60b3 0%,#c146a1 50%,#a80077 51%,#db36a4 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#cb60b3', endColorstr='#db36a4',GradientType=0 ); /* IE6-9 */
        font-family: "Helvetica", "Arial";


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

<script>

    Vue.use(require('vue-moment'));
    export default {
        props: [
            'msg'
        ],
        data() {
            return {
                
                // infos: [],
                topSixStudents: [],
                topSixEmployees: [],
                lastLogin:[],
                latestStudent: [],
                fullName: null,
                rfid: '',
                profilePic: null,
                marketingVideo: null,

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
                    console.log(key);
                });
            }
        },
        created() {
            // setInterval(function(){ 
            //     this.updateCurrentDayName();
            //     this.updateCurrentDay();
            //     this.updateCurrentMonth();
            //     this.updateCurrentTime();
            //     this.updateCurrentDate();
            //     this.updateCurrentYear();

            // }, 1000);

            setInterval(() => this.updateCurrentDayName(), 1 * 1000);
            setInterval(() => this.updateCurrentDay(), 1 * 1000);
            setInterval(() => this.updateCurrentMonth(), 1 * 1000);
            setInterval(() => this.updateCurrentTime(), 1 * 1000);
            setInterval(() => this.updateCurrentDate(), 1 * 1000);
            setInterval(() => this.updateCurrentYear(), 1 * 1000);

            Echo.channel('display-single-channel')

            .listen('SingleDisplay', (data) => {
                // console.log(data.marketingvideo);
               
                let dataLastStudent = data.laststudent;
                
                this.latestStudent = dataLastStudent;

                this.marketingVideo = data.marketingvideo;
                this.profilePic = data.schoollogo;

            }

            );

        }
    }
    
</script>


