<template>
    <li class="dropdown notifications-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
            <i class="fa fa-bell-o"></i>
            <span class="label label-danger" v-if="total > 0">{{ total }}</span>
        </a>


        <ul class="dropdown-menu" v-if="total > 0">
            <!-- <li class="header"></li> -->
            <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu" v-for="(notification, index) in notifications" :key="notification.id">
                    <li>
                        <a v-bind:href="notification.data.link + '?notification_id=' + notification.id">
                            <i class="fa fa-envelope text-yellow"></i> {{ notification.data.data }}
                        </a>
                    </li>
                    <!-- <li>
                        <a href="#">
                            <i class="fa fa-check text-blue"></i>Nothing for today!
                        </a>
                    </li> -->
                </ul>
            </li>
            <li class="footer"><a href="/admin/announcement">View all</a></li>
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
    </li>
</template>

<script>
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
                adminUrl: location.protocol + '//' + location.host + '/admin',
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
            getNotifications() {
                this.status = 'loading';

                var url = this.adminUrl + "/api/user/notification";
                console.log(url);
                axios.get(url)
	                .then(response => {
                        this.status         = 'done';
                        this.notifications  = response.data.data;
                        this.total          = response.data.total;
	                })
	                .catch(error => {
				        this.status = 'error';
				        new Noty({
	                      	title: 'Error',
	                      	text: 'Error, Something Went Wrong, Please Try To Reload The Page.',
	                      	type: "error"
	                  	}).show();
				    });
            }
        },
        created() {
            this.getNotifications();
	    },
        mounted() {
            let _this = this;

            Echo.private('App.User.' + _this.user.id)
                .notification((notification) => {
                    console.log('Notified ' + 'App.User.' + _this.user.id);

                    /**
                     * Notification Bell Reload Data
                     */
                    _this.getNotifications();
                });

            Echo.channel(_this.schoolId + '-employee-notification-channel')
                .listen('.' + _this.schoolId  + 'EmployeeNotificationEvent', function (response) {
                    console.log('Hello EmployeeNotificationEvent');
                    /**
                     * Notification Bell Reload Data
                     */
                    _this.getNotifications();
                });
        }
    };
</script>