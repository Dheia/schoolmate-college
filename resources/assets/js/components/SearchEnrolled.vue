<template>
    <div class="col-lg-5 col-md-9 col-sm-12 col-xs-12">
        <div class="col-md-10 col-sm-10 col-xs-10" style="padding: 0; margin: 0">
            <input  v-model="searchbar" @keyup.enter="searchNow()" type="text" class="form-control smo-searchbox" id="search" placeholder="Search student">
        </div>

        <div class="col-md-2 col-sm-2 col-xs-2"  style="padding: 0; margin: 0">
            <a href="#" @click="searchNow()" class="btn btn-primary btn-block form-control smo-search"><i class="fa fa-search"></i></a>
        </div>

        <!-- Students Modal -->
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
                    <center v-if="searchItems == null">
                        <img class="img-responsive" :src="this.magnify" alt="Magnifying Glass">
                        <h3>Searching for <span v-html="this.searchbar"></span></h3> 
                    </center>
                    <center v-if="searchItems != null">
                        <h3 v-if="searchItems.length < 1"> No Results Found. </h3>
                        <table class='table table-striped table-bordered' v-if="searchItems.length > 0">
                            <thead>
                                <tr>
                                    <th>Student No.</th>
                                    <th>Fullname</th>
                                    <th>Department</th>
                                    <th>Grade Level</th>
                                    <th>Term</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="enrollment in searchItems" :id="'student-' + enrollment.id">
                                    <td id='student-number'     style='vertical-align:middle'>{{ enrollment.studentnumber }}</td>
                                    <td id='student-fullname'   style='vertical-align:middle'>{{ enrollment.student.fullname }}</td>
                                    <td id='student-department' style='vertical-align:middle'>{{ enrollment.department_name }}</td>
                                    <td id='student-year'       style='vertical-align:middle'>{{ enrollment.level_name }}</td>
                                    <td id='student-year'       style='vertical-align:middle'>{{ enrollment.term_type + ' Term' }}</td>
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
    //import UserBookTransactions from './UserBookTransactions.vue';


    export default {
        props: ['school_year_id'],
        data() {
            return {
                magnify: '/images/magnify-glass-200px.gif',
                searchbar: null,
                searchItems: null,
                baseUrl: location.protocol + '//' + location.host,
                nextPage: null,
                prevPage: null,
                currentPage: null,
                lastPage: null,
                sy: this.school_year_id
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
                $('#searchString').text();
                $('#searchString').text(this.searchbar);
                this.searchItems = null;
                this.nextPage    = null;
                this.prevPage    = null;
                this.currentPage = null;
                this.lastPage    = null;
                // console.log(this.searchItems);
                $('#studentsModal').modal('toggle');
                axios.get('/admin/enrollment/api/get/student?search=' + this.searchbar + '&sy=' + this.sy)
                    .then(response => {
                        
                        this.searchItems = response.data.data;
                        this.nextPage    = response.data.next_page_url;
                        this.prevePage   = response.data.prev_page_url;
                        this.currentPage = response.data.current_page;
                        this.lastPage    = response.data.last_page;
                         // console.log(this.searchItems);
                    });

            },
        },

        created() {
            console.log("Reading");
        },
        components: {

        }
    };
</script>


