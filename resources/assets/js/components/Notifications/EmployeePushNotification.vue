
<template>
    <li class="dropdown notifications-menu">
        <a href="javascript:void(0)" class="dropdown-toggle dropdown-toggle-push-notif" data-toggle="dropdown" aria-expanded="true">
            <i class="fa fa-bell-o"></i>
            <span class="label label-danger"></span>
        </a>

        <VuePNotify></VuePNotify>
    </li>
</template>

<script>
    import _ from 'lodash';
 	import $ from "jquery";
 	import moment from 'moment';
    // import Noty from 'noty';
    import * as PusherPushNotifications from "@pusher/push-notifications-web";
    import axios from 'axios';

    export default {
        props: [
            'user',
            'school_id',
            'instance_id',
            'port'
        ],
        data() {
            return {
                portSocket: this.port,
                baseUrl: location.protocol + '//' + location.host,
                adminUrl: location.protocol + '//' + location.host + '/admin',
                schoolId: this.school_id,
                status: 'loading',
                instanceId: this.instance_id,
                beam_user_id: null,
                device_id: null,
                emp_user: this.user
            }
        },
        methods: {

            /**
             * Get Data For Pusher Beams
             */
            getUserPusherData: async function() {
                var url = this.adminUrl + "/api/pusher/user-data";
                await axios.get(url)
	                .then(response => {
                        console.log('Get Pusher Beams = ' . response.data);
                        this.instanceId        = response.data.instance_id;
                        this.user_beams_id      = response.data.user_beams_id;
                        this.device_interests   = response.data.device_interests;

                        console.log('Get Pusher Beams = ' . this.instanceId);
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

                    this.setPusherBeams();

                    $('.dropdown-toggle-push-notif').dropdown();
            },

            /**
             * Set Pusher Beams
             */
            setPusherBeams() {
                console.log('Set Pusher Beams');
                // this.beam_user_id = "web-" + this.emp_user.employee_id + '-' + this.schoolId + '-employee';
                this.beam_user_id = "web-" + this.schoolId + '-employee-' + this.emp_user.employee_id;
                console.log(this.beam_user_id);
                console.log(this.instanceId);

                try{
                    const tokenProvider = new PusherPushNotifications.TokenProvider({
                        // url: this.adminUrl + '/api/pusher/beams-auth?user_id=' + this.beam_user_id,
                        url: this.adminUrl + '/api/pusher/beams-auth',
                    });

                    const beamsClient = new PusherPushNotifications.Client({
                        instanceId: this.instanceId
                    });

                    beamsClient.getRegistrationState()
                        .then((state) => {
                            
                            let states = PusherPushNotifications.RegistrationState;

                            switch (state) {
                                case states.PERMISSION_DENIED: {
                                    console.log("PERMISSION DENIED");

    
                                    // Show message saying user should unblock notifications in their browser
                                    break;
                                }
                                case states.PERMISSION_GRANTED_REGISTERED_WITH_BEAMS: {
                                    console.log("PERMISSION_GRANTED_REGISTERED_WITH_BEAMS");
                                    // Ready to receive notifications
                                    // Show "Disable notifications" button, onclick calls '.stop'
                                    // beamsClient.setDeviceInterests(['debug-global', 'debug-employee'])
                                    // .then(() => console.log('Device interests have been set'))
                                    // .catch(e => console.error('Could not set device interests', e));
                                    
                                    console.log('web-'+this.school_id+'-employee')
                                    
                                    beamsClient.start()
                                        .then(() => beamsClient.addDeviceInterest('web-'+this.school_id+'-employee'))
                                        .then(() => beamsClient.addDeviceInterest('web-'+this.school_id+'-global'))
                                        .then(() => beamsClient.getDeviceInterests().then(
                                            interests => {
                                                console.log(interests) // Will log something like ["a", "b", "c"]
                                            }
                                        ))
                                    break;
                                }
                                case states.PERMISSION_GRANTED_NOT_REGISTERED_WITH_BEAMS: {
                                    console.log("PERMISSION_GRANTED_NOT_REGISTERED_WITH_BEAMS");
                                    this.startBeams(beamsClient)
                                    break;
                                }
                                case states.PERMISSION_PROMPT_REQUIRED: {
                                    console.log("PERMISSION_PROMPT_REQUIRED");
                                    // Need to call start before we're ready to receive notifications
                                    // Show "Enable notifications" button, onclick calls '.start'
                                    //SET USER ID HERE TRY IT

                                    beamsClient.start()
                                        .then(() => beamsClient.setUserId(this.beam_user_id, tokenProvider))
                                        .then(() => console.log('User ID has been set'))
                                        .catch(e => console.error('Could not authenticate with Beams:', e));
                                
                                    break;
                                }
                            }
                        })
                        .catch((e) => console.error("Could not get registration state", e));

                } catch(error) {
                    this.$notify({
                        // title: 'Error',
                        text: 'Error, Something Went Wrong, Please Try To Reload The Page.',
                        style: "error",
                        // icon: 'fa fa-exclamation-triangle',
                    });
                    console.error(error);
                }   
            },

            /**
             * Start Pusher Beams
             */
            startBeams(beamsClient) {
                console.log('Start Pusher Beams');
                beamsClient.stop();
                beamsClient.start()
                    .then(() => beamsClient.getDeviceId())
                    .then(deviceId => {
                        console.log(deviceId) // Will log something like web-1234-1234-1234-1234
                    })
                    .catch(e => console.error('Could not get device id', e));
            }
        },
        created() {
            // this.getUserPusherData();
            // new PNotify({
            //     // title: 'Regular Notice',
            //     text: "Hi",
            //     type: "error",
            //     icon: false
            //   });

            // if(Notification.permission != 'denied') {
            //     this.setPusherBeams();
            // }
	    },
        mounted() {
            let _this = this;

             if(Notification.permission != 'denied') {
                this.setPusherBeams();
            }   

        }
    };
</script>