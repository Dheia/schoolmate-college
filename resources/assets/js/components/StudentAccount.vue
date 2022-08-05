<template>
    <div>
        <div style="display:flex;">
            <div class="col-md-3 col-sm-3" style="padding-left: 20px; padding-top:20px; padding-right:0; margin: 0; ">
                <input v-model="searchbar" @keyup.enter="searchNow()" type="text" class="form-control" id="search" style="border-top-left-radius:15px;border-bottom-left-radius:15px;">
            </div>
            <div class="col-md-1 col-sm-2"  style="padding-top: 20px; padding-right:20px; margin: 0;padding-left:0;">
                <a href="#" @click="searchNow()" class="btn btn-primary btn-block" style="border-radius: 0;height:34px;border-top-right-radius:15px;border-bottom-right-radius:15px;"><i class="fa fa-search"></i></a>
            </div>
        </div>

      <!-- CONTENT INFORMATION -->
        <div class="row" style="padding: 20px;padding-top:20px">
          <div class="col-md-12 col-lg-12">
            <div class="info-box shadow">
              <div class="box-body" style="padding-top:25px;">
                <div class="col-md-2 col-lg-2">
                  <span class="info-box-text text-info">Student ID</span>
                  <span class="info-box-number" v-if="selectedStudent != null">{{ selectedStudent.studentnumber }}</span>
                </div>
                <div class="col-md-3 col-lg-3">
                  <span class="info-box-text text-info">Full Name</span>
                  <span class="info-box-number" v-if="selectedStudent != null">{{ selectedStudent.fullname }}</span>
                </div>
                <div class="col-md-3 col-lg-3">
                  <span class="info-box-text text-info">Department:</span>
                  <span class="info-box-number" v-if="selectedStudent != null">{{ selectedStudent.department_name }}</span>
                </div>
                <div class="col-md-2 col-lg-2">
                  <span class="info-box-text text-info">Level:</span>
                  <span class="info-box-number"v-if="selectedStudent != null">{{ selectedStudent.current_level }}</span>
                </div>

                <div class="col-md-2 col-lg-2">
                  <span class="info-box-text text-info">Track:</span>
                  <span class="info-box-number"v-if="selectedStudent != null">{{ selectedStudent.track_name}}</span>
                </div>
              </div>
            </div>
          </div>
        </div>  
      <!-- END OF CONTENT INFORMATION -->


            <div v-if="listOfYears == null" class="col-md-12 " id="first-view" style="margin-top: 40px;">
                <h4 class="text-center">SEARCH FOR STUDENT ACCOUNT</h4> 
                <i class="fa fa-users fa-5x text-center" style="display: block; padding-bottom: 50px;"></i>
            </div>
    

            <div id="tuition-info" class="col-md-12 sa-search-results" style="padding-left: 20px;padding-right:20px;">
                <!-- <tuition-fee></tuition-fee> -->
                <list-of-years :listYears="listOfYears" v-if="listOfYears !== null"></list-of-years>
            </div>



            <!-- Modal -->
            <div id="studentsModal" class="modal fade search-modal" role="dialog">
                <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Search Results&nbsp;
                            <small>
                                [ <span id="currentPage">{{ currentPage }}</span> - <span id="lastPage">{{ lastPage }}</span> ]
                            </small>
                        </h4>
                    </div>
                    <div class="modal-body">
                        
                        <center>
                            <table class='table table-striped table-bordered search-modal-results'>
                                <thead >
                                    <th>Student No.</th>
                                    <th>Fullname</th>
                                    <th>Department</th>
                                    <th>Grade Level</th>
                                    <th>Track</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    
                                    <tr v-for="student in searchItems" :id="'student-' + student.id">
                                        <td id='student-number'     style='vertical-align:middle'>{{ student.studentnumber }}</td>
                                        <td id='student-fullname'   style='vertical-align:middle'>{{ student.fullname }}</td>
                                        <td id='student-department'   style='vertical-align:middle'>{{ student.department_name }}</td>
                                        <td id='student-year'       style='vertical-align:middle'>{{ student.current_level }}</td>
                                
                                        <td id='student-track'      style='vertical-align:middle'>{{ student.track_name }}</td>
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

                axios.get('/admin/api/get/student?search=' + this.searchbar)
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
                axios.get('/admin/api/get/enrollments-year/' + studentnumber)
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
    };
</script>
<style type="text/css">
    .search-modal-results >thead> th{
        padding-top:10px;
        padding-bottom:10px;
        padding-left: 10px;
    }

</style>


