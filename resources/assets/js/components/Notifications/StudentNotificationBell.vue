<template>
    <li class="dropdown notifications-menu">
        <a href="javascript:void(0)" class="dropdown-toggle-notification" data-toggle="dropdown" aria-expanded="true">
            <i class="fa fa-bell-o"></i>
            <span class="label label-danger" v-if="total > 0">{{ total }}</span>
        </a>


        <ul class="dropdown-menu" v-if="total > 0">
            <!-- <li class="header"></li> -->
            <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu" v-for="(notification, index) in notifications" :key="notification.id">

                    <!-- Notification Content -->
                    <li>
                        <a v-bind:href="studentUrl + notification.data.link">
                            <div class="row" style="padding-bottom: 0;">
                                <div class="col-md-1">
                                    <i class="fa fa-envelope text-yellow"></i> 
                                </div>
                                <div class="col-md-10">
                                    <span :class="notification.data.type + '-notif-content'" v-html="notification.data.message"></span>
                                </div>
                            </div>
                        </a>
                    </li>

                </ul>
            </li>
            <li class="footer"><a :href="studentUrl + '/notification'">View all</a></li>
        </ul>

        <ul class="dropdown-menu" v-else>
            <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                    <li>
                        <a href="javascript:void(0)">
                            <i class="fa fa-check text-blue"></i>Nothing for today!
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <VuePNotify></VuePNotify>
        
    </li>
</template>

<style>
    .announcement-notif-content {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }
    .online-post-notif-content {
        white-space: break-spaces;
    }
</style>

<script>
    import _ from 'lodash';
 	import $ from "jquery";
 	import moment from 'moment';

    export default {
        props: [
            'user',
            'school_id',
            'port'
        ],
        data() {
            return {
                portSocket: this.port,
                baseUrl: location.protocol + '//' + location.host,
                studentUrl: location.protocol + '//' + location.host + '/student',
                schoolId: this.school_id,
                status: 'loading',
                notifications: [],
                total: 0
            }
        },
        methods: {
            /**
             * Get Notifications
             */
            getNotifications: async function() {
                this.status = 'loading';

                var url = this.studentUrl + "/api/notification";
                console.log(url);
                await axios.get(url)
	                .then(response => {
                        this.status         = 'done';
                        this.notifications  = response.data.data;
                        this.total          = response.data.total;
	                })
	                .catch(error => {
				        this.status = 'error';

                        this.$notify({
                            // title: 'Error',
                            text: 'Error, Something Went Wrong, Please Try To Reload The Page.',
                            style: "error",
                            // icon: 'fa fa-exclamation-triangle',
                        });
				    });

                    $('.dropdown-toggle-notification').dropdown();
            }
        },
        created() {
            // this.getNotifications();
	    },
        mounted() {
            let _this = this;

            _this.getNotifications();

            Echo.private(_this.schoolId + '.App.StudentCredential.' + _this.user.id)
                .notification((notification) => {
                    console.log('Notified ' + 'App.StudentCredential.' + _this.user.id);

                    /**
                     * Notification Bell Reload Data
                     */
                    _this.getNotifications();
                });

            Echo.channel(_this.schoolId + '-student-notification-channel')
                .listen('.' + _this.schoolId  + 'StudentNotificationEvent', function (response) {
                    console.log('Hello StudentNotificationEvent');
                    /**
                     * Notification Bell Reload Data
                     */
                    _this.getNotifications();
                });
        }
    };
</script>