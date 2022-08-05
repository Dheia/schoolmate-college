<template>

    <div class="container-fluid" style="padding: 0; margin: 0;">

            <div class="col-md-12" style="padding: 0;">
                <table class="table table-bordered mb-0">
                    <tbody>
                        <tr>
                            <td><b><small>Student ID:</small></b></td>
                            <td id="studentID">&nbsp; 
                                <span v-if="selectedStudent != null">{{ selectedStudent.studentnumber }}</span>
                            </td>
                            
                            <td><b><small>Fullname:</small></b></td>
                            <td id="fullname">&nbsp; 
                                <span v-if="selectedStudent != null">{{ selectedStudent.fullname }}</span>
                            </td>
                            
                            <td><b><small>Current Enrolled:</small></b></td>
                            <td id="gradeLevel">&nbsp; 
                                <span v-if="selectedStudent != null">{{ selectedStudent.current_enrollment }}</span>
                            </td>
                            
                            <td><b><small>Year:</small></b></td>
                            <td id="year">&nbsp; 
                                <span v-if="selectedStudent != null">{{ selectedStudent.school_year.schoolYear }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-11 col-sm-10" style="padding: 0; margin: 0">
                <input v-model="searchbar" @keyup.enter="searchNow()" type="text" class="form-control" id="search">
            </div>
            <div class="col-md-1 col-sm-2"  style="padding: 0; margin: 0">
                <a href="#" @click="searchNow()" class="btn btn-primary btn-block" style="border-radius: 0;">Search</a>
            </div>
    

            <div id="tuition-info" class="col-md-12" style="padding: 0;">


                <!-- <tuition-fee></tuition-fee> -->
                <list-of-years :listYears="listOfYears" v-if="listOfYears !== null"></list-of-years>
            </div>



            <!-- Modal -->
            <div id="studentsModal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Search &nbsp;
                            <small>
                                [ <span id="currentPage">{{ currentPage }}</span> - <span id="lastPage">{{ lastPage }}</span> ]
                            </small>
                        </h4>
                    </div>
                    <div class="modal-body">
                        
                        <center>
                            <table class='table table-striped table-bordered'>
                                <thead>
                                    <th>StudentNumber</th>
                                    <th>Fullname</th>
                                    <th>Grade Level</th>
                                    <th>Year</th>
                                    <th>SELECT</th>
                                </thead>
                                <tbody>
                                    
                                    <tr v-for="student in searchItems" :id="'student-' + student.id">
                                        <td id='student-number'     style='vertical-align:middle'>{{ student.studentnumber }}</td>
                                        <td id='student-fullname'   style='vertical-align:middle'>{{ student.fullname }}</td>
                                        <td id='student-level'      style='vertical-align:middle'>{{ student.year_management.year }}</td>
                                        <td id='student-year'       style='vertical-align:middle'>{{ student.school_year.schoolYear }}</td>
                                        <td>
                                            <a href='#' @click="selectStudent(student.id)" class='btn btn-primary btn-block'>Select</a>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                            <nav aria-label="Page navigation example">
                              <ul class="pagination justify-content-center m-b-0 m-t-0">
                                <li class="page-item">
                                  <a href="javascript:void(0)" class="page-link" @click="requestPage(prevPage)" v-if="prevPage !== null">Previous</a>
                                </li>
                                <li class="page-item">
                                  <a href="javascript:void(0)" class="page-link" @click="requestPage(nextPage)" v-if="nextPage !== null">Next</a>
                                </li>
                              </ul>
                            </nav>
                        </center>
                        
                    </div>
                </div>

                </div>
            </div>

    </div>


</template>

<script>
    
    import ListOfYears from './ListOfYears.vue';


    export default {
        data() {
            return {
                searchbar: null,
                searchItems: [],
                baseUrl: location.protocol + '//' + location.host,
                selectedStudent: null,  
                listOfYears: null,
                nextPage: null,
                prevPage: null,
                currentPage: null,
                lastPage: null,
            }
        },
        methods: {

            requestPage(url) {
                 axios.get(url)
                    .then(response => {
                        this.searchItems = response.data.data;
                        this.nextPage    = response.data.next_page_url;
                        this.prevPage    = response.data.prev_page_url;
                        this.currentPage = response.data.current_page;
                        this.lastPage    = response.data.last_page;
                    });
            },

            searchNow() {
                if(this.searchbar == null || this.searchbar === '') {
                    alert("Please enter a keyword");
                    return false;
                }

                axios.get(this.baseUrl + '/admin/api/get/student?search=' + this.searchbar)
                    .then(response => {
                        
                        this.searchItems = response.data.data;
                        this.nextPage    = response.data.next_page_url;
                        this.prevePage   = response.data.prev_page_url;
                        this.currentPage = response.data.current_page;
                        this.lastPage    = response.data.last_page;

                        $('#studentsModal').modal('toggle');
                    });
            },

            getTuitionList(studentNumber) {
                alert(studentNumber);
                // axios.get(this.baseUrl + '/admin/api/get/tuitions/' + this.searchbar)
                //     .then(response => {

                //     });
            },

            getListOfYear (studentnumber) {
                axios.get(this.baseUrl + '/admin/api/get/enrollments-year/' + studentnumber)
                        .then(response => {
                            this.listOfYears = response.data;
                        });
            },

            selectStudent(idx) {
                this.selectedStudent = this.searchItems.find(item => item.id == idx);
                $('#studentsModal').modal('toggle');
                this.searchbar = null,
                // this.getTuitionList(this.selectedStudent.studentnumber);
                this.getListOfYear(this.selectedStudent.studentnumber);
            }
        },

        created() {
            console.log("Reading");    
            jQuery(function ($) {
                $('form').submit(false);
            }) 
        },
        components: {
            ListOfYears,
        }
    }
    
</script>


