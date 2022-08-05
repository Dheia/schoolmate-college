<template>
    <div style="width: 100%;">
        

        <!-- FIRST STEP -->
        <div class="first" v-if="!steps.first.done">
            
            <div class="container-login100-form-btn" style="padding-top: 0;">
                <span class="login100-form-title">
                    Are You Enrolling As {{ nextgradelevel.level.year }} - {{ nextgradelevel.term_type }} <span v-if="nextgradelevel.term_type !== null">Term</span> ?
                </span>
            </div>
            
            <div class="container-login100-form-btn" style="padding-top: 0;">
                    
                <div class="col-lg-5" style="padding: 0;">
                    <button @click="firstStep(1)" class="login100-form-btn">Yes</button>
                </div>
                <div class="col-lg-2"></div>
                <div class="col-lg-5" style="padding: 0;">
                    <a href="/kiosk/enlisting/old" class="login100-form-btn btn-primary" style="background-color: #007bff;">No</a>
                </div>

            </div>

        </div>
        <!-- END OF FIRST STEP -->
        

        <!-- SECOND STEP -->
        <!-- steps.first.done && -->

        <div class="second" v-if="steps.first.done && !steps.second.done && tuition == null && show_tuition">
            <div class="container-login100-form-btn" style="padding-top: 0;">
                <span class="login100-form-title">No Tuition Found</span>
                <button @click="secondStep('back')" class="login100-form-btn">Back</button>
            </div>
        </div>

        <div v-else>
            <div class="second" v-if="steps.first.done && !steps.second.done">
                <div class="container-login100-form-btn" style="padding-top: 0;">
                    <span class="login100-form-title">Choose Payment Basis</span>
                </div>
                
                <div class="container-login100-form-btn" style="padding-top: 0;">

                    <table :class="mobile ? 'table table-striped table-responsive' : 'table table-striped'" style="background-color: #FFF;">
                        <thead>
                            <th><small><b>Name</b></small></th>
                            <!-- <th><small><b>Price</b></small></th> -->
                            <th><small><b>Action</b></small></th>
                        </thead>
                        <tbody>
                            <tr v-for="(payment, index) in commitmentpayment" v-if="payment.active">
                                <td><small>{{ payment.name }}</small></td>
                                <!-- <td><small><b>P</b>{{ tuition.grand_total[payment.id - 1].amount | formatNumber }}</small></td> -->
                                <td>
                                    <button v-if="payment.id !== steps.second.payment_id" @click="selectPaymentBasis(payment.id, index, payment.snake)" class="btn btn-primary btn-xs">Select</button>
                                    <button v-else="payment.id == steps.second.payment_id" class="btn btn-primary btn-xs" disabled>Selected</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="col-lg-5" style="padding: 0;">
                        <button @click="secondStep('back')" class="login100-form-btn">Back</button>
                    </div>
                    <div class="col-lg-2"></div>
                    <div class="col-lg-5" style="padding: 0;">
                        <button v-if="steps.second.payment_id !== null" @click="secondStep('review')" class="login100-form-btn btn-primary" style="background-color: #007bff;">Review</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END OF SECOND STEP -->

        <div class="third" v-if="steps.first.done && steps.second.done && !steps.third.done">
            <div class="container-login100-form-btn" style="padding-top: 0;">
                <span class="login100-form-title">Enter Email Address</span>
            </div>
        
            <div class="container-login100-form-btn" style="padding-top: 0;">
                    
                <div class="form-group col-md-12">
                    <input id="email" type="email" v-model="steps.third.email" @keyup="validateEmail" class="form-control" placeholder="Enter your email address" required autocomplete="off">
                </div>

                <div class="col-lg-5" style="padding: 0;">
                    <button @click="thirdStep('back')" class="login100-form-btn">Back</button>
                </div>
                <div class="col-lg-2"></div>
                <div class="col-lg-5" style="padding: 0;">
                    <button v-if="steps.third.email_valid" @click="thirdStep('next')" class="login100-form-btn btn-primary" style="background-color: #007bff;">Next</button>
                </div>
            </div>
        </div>

        <!-- FOURTH STEP (REVIEW) --> 
        <!-- steps.third.done && -->
        <div class="fourth" v-if="steps.first.done && steps.second.done && steps.third.done && !steps.fourth.done">
            
            <div class="container-login100-form-btn" style="padding-top: 0;">
                <span class="login100-form-title">Review</span>
            </div>
        
            <div class="container-login100-form-btn" style="padding-top: 0;">
                
                <table :class="mobile ?  'table table-striped table-responsive' : 'table table-striped'" style="background-color: #FFF">
                    <tbody>
                        <tr>
                            <td><small><strong>Student No.</strong></small></td>
                            <td><small>{{ schoolabbr }} - {{ studentnumber }}</small></td>
                        </tr>
                        <tr>
                            <td><small><strong>Full Name</strong></small></td>
                            <td><small>{{ student.fullname }}</small></td>
                        </tr>
                        <tr v-if="show_tuition">
                            <td><small><strong>Tuition Form</strong></small></td>
                            <td><small>{{ tuition.form_name }}</small></td>
                        </tr>
                        <tr>
                            <td><small><strong>Enrolling As</strong></small></td>
                            <td><small>{{ nextgradelevel.level.year }}  - {{ nextgradelevel.term_type }} Term | {{ schoolyearactive.schoolYear }}</small></td>
                        </tr>
                        <tr>
                            <td><small><strong>Payment Basis</strong></small></td>
                            <td><small>{{ commitmentpayment[steps.second.payment_index].name }}</small></td>
                        </tr>
                        <tr v-if="show_tuition">
                            <td><small><strong>Tuition Fee</strong></small></td>
                            <!-- <td v-for="tuitionFee in tuition.tuition_fees" v-if="tuitionFee.payment_type == steps.second.payment_id"><small>P {{ tuitionFee.total | formatNumber }}</small></td> -->
                            <td><small><b>P {{ getTotalFee | formatNumber }}</b></small></td>
                        </tr>
                        
                        <!-- UPON ENROLLMENT -->
                        <div v-if="show_tuition">
                            <tr v-for="uponEnrollment in tuition.total_payable_upon_enrollment" 
                            v-if="uponEnrollment.payment_type == steps.second.payment_id">
                                <td style="text-indent: 30px; padding: 0;">
                                    <small style="font-size: 70%;">- Upon Enrollment</small>
                                </td>
                                <td style="text-indent: 30px; padding: 0;">
                                    <small style="font-size: 70%;">
                                        P {{ uponEnrollment.amount | formatNumber }}
                                    </small>
                                </td>
                            </tr>
                        </div>
                        <!-- UPON ENROLLMENT -->
                        
                        <!-- TOTAL PAYMENT SCHEME -->
                        <div v-if="show_tuition">
                            <tr v-for="totalPaymentScheme in tuition.total_payment_scheme" 
                                v-if="totalPaymentScheme.payment_type == steps.second.payment_id">
                                <td style="text-indent: 30px; padding: 0;">
                                    <small style="font-size: 70%;">- Balance</small>
                                </td>
                                <td style="text-indent: 30px; padding: 0;">
                                    <small style="font-size: 70%;">
                                        P {{ totalPaymentScheme.amount | formatNumber }}
                                    </small>
                                </td>
                            </tr>
                        </div>
                        <!-- TOTAL PAYMENT SCHEME -->


                        <tr v-if="show_tuition">
                            <td><small><strong>Activity Fees</strong></small></td>
                            <td><small><b>P {{ tuition.total_activities | formatNumber }}</b></small></td>
                        </tr>
                        
                        <!-- ACTIVTIES FEE ITEM -->
                        <div v-if="show_tuition">
                            <tr v-for="activity in tuition.activities_fee">
                                <td style="text-indent: 30px; padding: 0;">
                                    <small style="font-size: 70%;">- {{ activity.code + ' ' + activity.description }}</small>
                                </td>
                                <td style="text-indent: 30px; padding: 0;">
                                    <small style="font-size: 70%;">
                                        P {{ activity.amount | formatNumber }}
                                    </small>
                                </td>
                            </tr>
                        </div>
                        <!-- ACTIVTIES FEE ITEM -->

                        <tr v-if="show_tuition">
                            <td><small><strong>Miscellaneous Fees</strong></small></td>
                            <td><small><b>P {{ tuition.total_miscellaneous | formatNumber }}</b></small></td>
                        </tr>

                        <!-- SUB MISCELLANEOUS -->
                        <div v-if="show_tuition">
                            <tr v-for="misc in tuition.miscellaneous">
                                <td style="text-indent: 30px; padding: 0;"><small style="font-size: 70%;">
                                    - {{ misc.code }} {{ misc.description }}</small>
                                </td>
                                <td style="text-indent: 30px; padding: 0;">
                                    <small style="font-size: 70%;">
                                        P {{ misc.amount | formatNumber }}
                                    </small>
                                </td>
                            </tr>
                        </div>
                        <!-- SUB MISCELLANEOUS -->

                        <tr v-if="show_tuition">
                            <td><small><strong>Other Fees</strong></small></td>
                            <td><small><b>P {{ tuition.total_other_fees | formatNumber }}</b></small></td>
                        </tr>


                        <!-- OTHER FEE ITEM -->
                        <div v-if="show_tuition">
                            <tr v-for="other in tuition.other_fees">
                                <td style="text-indent: 30px; padding: 0;">
                                    <small style="font-size: 70%;">- {{ other.code + ' ' + other.description }}</small>
                                </td>
                                <td style="text-indent: 30px; padding: 0;">
                                    <small style="font-size: 70%;">
                                        P {{ other.amount | formatNumber }}
                                    </small>
                                </td>
                            </tr>
                        </div>
                        <!-- OTHER FEE ITEM -->

                        <tr v-if="show_tuition">
                            <td><small style="font-size: 100%;"><b>Total Amount Due</b></small></td>
                            <td v-for="grandTotal in tuition.grand_total" v-if="grandTotal.payment_type == steps.second.payment_id">
                                <small style="font-size: 100%;">
                                    <b>P {{ grandTotal.amount | formatNumber }}</b>
                                </small>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="table">
                    <div class="form-group col-md-12 col-lg-12">
                        <div class="form-check">
                            <input  v-model="agree" class="form-check-input" type="checkbox" value="" id="terms_conditions" name="terms_conditions">
                            <label class="form-check-label" for="terms_conditions" style="color: #000;">
                                I agree to the <a id="terms_conditions_link" href="/kiosk/enlisting/privacy" target="_blank"><strong>terms, conditions and data privacy.</strong></a>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5" style="padding: 0;">
                    <button @click="fourthStep('back')" class="login100-form-btn">Back</button>
                </div>
                <div class="col-lg-2"></div>
                <div class="col-lg-5" style="padding: 0;">
                    <button v-if="agree" @click.once="submit()" class="login100-form-btn btn-primary" id="submitBtn" style="background-color: #007bff;">Submit</button>
                </div>
            </div>
        </div>
        <!-- END OF FOURTH STEP -->

        <!-- FOURTH STEP (REVIEW) --> 
        <!-- steps.second.done && -->
        <div class="fourth" v-if="steps.first.done && steps.second.done && steps.third.done && steps.fourth.done">
            <div v-if="additional_page">
                <div v-if="additional_page.active == 0" class="container-login100-form-btn" style="padding-top: 0;">
                    <span class="login100-form-title">ENROLLMENT IS NOW BEING PROCESSED!</span>
                </div>
                <!-- IF SCHOOL ID IS MDSI -->
                <div v-if="additional_page.active == 1" class="container-login100-form-btn" style="padding-top: 0;">

                    <span class="login100-form-title" v-html="additional_page.description">
                    </span>
                    <br>
                </div>
            </div>
            <div class="container-login100-form-btn" style="padding-top: 0;">
                <div class="col-lg-12" style="padding: 0;">
                    <a href="/kiosk/enlisting" class="login100-form-btn btn-primary" style="background-color: #007bff;">Back To Home</a>
                </div>
            </div>
        </div>
        <!-- END OF FIFTH STEP -->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                current_enrolled: null,
                selected_tuition_id: this.tuition ? this.tuition.id : null,
                enrollment_status_item: this.enrollmentstatusitem,
                track_id: null,
                agree: false,
                show_tuition: true,

                steps: {
                    first: {
                        done: false,
                        is_enrolling: null,
                    }, 
                    second: {
                        done: false,
                        payment_name: null,
                        payment_id: null,
                        payment_index: null,
                        tuition_index: null,
                    },
                    third: {
                        done: false,
                        email: null,
                        email_valid: false,
                    },
                    fourth: {
                        done: false
                    },
                },
                mobile: false,
                additional_page: null
            };
        },

        mounted() {
            if(this.enrollment !== null) {
                this.current_enrolled = this.nextgradelevel.year;
            } else {

            }

            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                this.mobile = true;
            }
        },

        methods: {

            termsCheck(){
                alert(this.target.checked);
            },
            
            firstStep(_bool) {
                this.steps.is_enrolling = _bool; 
                this.steps.first.done   = true;
                console.log(this.show_tuition);
                // this.getKioskTuitionSetting();

                // if(! this.show_tuition) {
                //     this.secondStep('review');
                // }
            },

            secondStep(res) {

                if(res == "back") {
                    // this.steps.is_enrolling = null;
                    this.steps.first.done = false;
                }

                if(res == 'review') {
                    this.steps.second.done = true;
                }

            },

            thirdStep(res) {
                if(res == 'back') {
                    this.steps.second.done = false;
                }

                if(res == 'next') {
                    this.steps.third.done = true;
                }
            },

            fourthStep(res) {
                if(res == 'back') {
                    this.steps.third.done = false;
                }
            },


            // selectTuition (id, idx) {
            //     this.steps.second.selected_tuition_id = id;
            //     this.steps.second.tuition_index          = idx;
            // },

            selectPaymentBasis (id, idx, name) {
                this.steps.second.payment_id    = id;
                this.steps.second.payment_index = idx;
                this.steps.second.payment_name  = name;
            },

            previewTuition (id) {
                console.log("TUITION PREVIEW : ", id);
            },

            getKioskTuitionSetting ()
            {
                axios.get('/kiosk/enlisting/tuition-setting')
                    .then(response => {
                        if(response.data) {
                            this.show_tuition = response.data.active ? true : false;
                            if(! this.show_tuition) {
                                this.selected_tuition_id = null;
                                this.tuition = null; 
                            }
                        }
                    })
                    .catch(error => {
                        // console.log(error);
                        new PNotify({
                            title: 'Error',
                            text: 'Error, Something Went Wrong, Please Try To Reload The Page.',
                            type: "error"
                        });
                    });
            },

            validateEmail () {
                var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                
                if(this.steps.third.email !== null) {
                    if(this.steps.third.email.match(mailformat)) {
                        this.steps.third.email_valid = true;
                    }
                    else {
                        this.steps.third.email_valid = false;
                    }
                }
            },

            submit () {
                if(this.nextgradelevel.track)
                {
                    this.track_id = this.nextgradelevel.track.id;
                }
                $('#submitBtn').text('Submitting...');
                axios.post('/kiosk/enlisting/old/'+this.enrollment_status_item+'/submit', {
                    studentnumber   : this.studentnumber,
                    tuition_id      : this.selected_tuition_id,
                    grade_level_id  : this.nextgradelevel.level.id,
                    term_type       : this.nextgradelevel.term_type ? this.nextgradelevel.term_type : null,
                    schoolyear_id   : this.schoolyearactive.id,
                    payment_id      : this.steps.second.payment_id,
                    email           : this.steps.third.email,
                    curriculum_id   : null,
                    track_id        : this.track_id,
                    enrollment_status_item : this.enrollment_status_item,
                    show_tuition    : this.show_tuition


                }).then(res => {
                    // alert(res.data.status);
                    if(res.data.status === "OK") {
                        $('#submitBtn').text('Submitted');
                        this.steps.fourth.done = true;
                        $('#submitBtn').text('Submit');
                        this.additional_page = res.data.additionalPage;
                        return;
                    } else {
                        $('#submitBtn').text('Error Submitting...');
                    }
                }).catch(error => {
                    $('#submitBtn').text('Submit');
                    // console.log(error);
                });
            }
        },

        computed: {
            getTotalFee () {
                var upon = _.find(this.tuition.total_payable_upon_enrollment, { payment_type: this.steps.second.payment_id });
                var pScheme = _.find(this.tuition.total_payment_scheme, { payment_type: this.steps.second.payment_id });
                console.log('upon ', upon);
                console.log('pScheme ', pScheme);
                return upon.amount + pScheme.amount;
            }
        },

        created() {
            this.getKioskTuitionSetting();
        },

        props: ['schoolabbr', 'enrollment', 'student', 'studentnumber', 'nextgradelevel', 'schoolyearactive', 'tuition', 'commitmentpayment', 'school_id', 'enrollmentstatusitem']
    }
</script>
