<template>

    <!-- Search Section -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!-- Search Input -->
        <div class="col-md-10 col-sm-10 col-xs-10 no-padding no-margin">
            <input v-model="searchbar" @keyup.enter="searchNow()" type="text" class="form-control smo-searchbox" id="search" placeholder="Search student">
        </div>

        <!-- Search Button -->
        <div class="col-md-2 col-sm-2 col-xs-2 no-padding no-margin">
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
                        <!-- Searching GIF / Magnifying Glass GIF -->
                        <div v-if="isSearching">
                            <img class="img-responsive" v-bind:src="'/images/magnify-glass-200px.gif'" alt="Searching..." style="margin: auto;">
                            <h3 class="text-center">Searching for "{{ searchbar }}"</h3>
                        </div>
                        <center v-else>
                            <!-- Loading GIF -->
                            <div v-if="isLoading">
                                <img class="img-responsive" v-bind:src="'/vendor/backpack/crud/img/ajax-loader.gif'" alt="Loading..." style="margin: auto;">
                            </div>

                            <table class='table table-striped table-bordered' v-else-if="totalSearch > 0">
                                <thead>
                                    <tr>
                                        <th>Student No.</th>
                                        <th>Fullname</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="student in searchItems" :id="'student-' + student.id">
                                        <td style='vertical-align:middle'>{{ student.studentnumber }}</td>
                                        <td style='vertical-align:middle'>{{ student.fullname }}</td>

                                        <td style='vertical-align:middle'>
                                            <a class="btn btn-xs btn-primary action-btn" v-bind:href="'student/' + student.id + '/record'" target="_blank" style="width: 100%;">
                                                <i class="fas fa-clipboard-list"></i> Record
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <h3 class="text-center" v-else>No search result for "{{ searchbar }}"</h3>
                    
                            <nav aria-label="Page navigation example" v-if="!isLoading && totalSearch > 0">
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
        <!-- End of Students Modal -->
    </div>
    <!-- End of Search Section -->


</template>

<script>
    //import UserBookTransactions from './UserBookTransactions.vue';


    export default {
        data() {
            return {
                searchbar: null,
                searchItems: [],
                baseUrl: location.protocol + '//' + location.host,
                nextPage: null,
                prevPage: null,
                currentPage: null,
                lastPage: null,
                searchQuery: '',
                totalSearch: 0,
                isSearching: false,
                isLoading: false
            }
        },
        methods: {

            requestPage(url) {
                isLoading: true;
                 axios.get(url)
                    .then(response => {
                        this.searchItems = response.data.data;
                        if(response.data.next_page_url){    
                            this.nextPage    = response.data.next_page_url+ '&search=' + this.searchbar;
                        }
                        else{
                            this.nextPage    = response.data.next_page_url;
                        }
                        if(response.data.prev_page_url){    
                            this.prevPage   = response.data.prev_page_url+ '&search=' + this.searchbar;
                        }
                        else{
                            this.prevPage   = response.data.prev_page_url;
                        }
                        this.currentPage = response.data.current_page;
                        this.lastPage    = response.data.last_page;
                        isLoading: false;
                    });
                    $('.dropdown-toggle').dropdown();
            },

            searchNow() {
                if(this.searchbar == null || this.searchbar === '') {
                    alert("Please enter a keyword");
                    return false;
                }
                this.isSearching = true;
                $('#studentsModal').modal('toggle');

                axios.get('/admin/student/api/get/student?search=' + this.searchbar)
                    .then(response => {
                        
                        this.searchItems = response.data.data;
                        if(response.data.next_page_url){    
                            this.nextPage    = response.data.next_page_url+ '&search=' + this.searchbar;
                        }
                        else{
                            this.nextPage    = response.data.next_page_url;
                        }
                        if(response.data.prev_page_url){    
                            this.prevPage   = response.data.prev_page_url+ '&search=' + this.searchbar;
                        }
                        else{
                            this.prevPage   = response.data.prev_page_url;
                        }
                        this.currentPage = response.data.current_page;
                        this.lastPage    = response.data.last_page;
                        this.totalSearch = response.data.total;
                        this.isSearching = false;
                    });
                    $('.dropdown-toggle').dropdown();
            },
        },

        created() {
            // console.log("Reading");
        },
        components: {

        }
    };
</script>


