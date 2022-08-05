<div class="container-fluid" style="padding: 0; margin: 0;">
    
    <!-- Search Student Start -->
    <div class="col-md-11 col-sm-10" style="padding: 0; margin: 0">
        <input v-model="searchbar" id="searchbar" @keyup.enter="searchNow()" type="text" class="form-control">
    </div>
    <div class="col-md-1 col-sm-2"  style="padding: 0; margin: 0">
        <a href="#" onclick="searchNow()" class="btn btn-primary btn-block" style="border-radius: 0;">Search</a>
    </div>
    <!-- Search Student End -->
    
    <div v-if="listOfYears == null" class="col-md-12 " id="first-view" style="margin-top: 40px;">
        <h4 class="text-center">SEARCH FOR STUDENT ACCOUNT</h4> 
        <i class="fa fa-users fa-5x text-center" style="display: block; padding-bottom: 50px;"></i>
    </div>

    <!-- Student Information Div Start-->
    <div class="col-md-12 " id="student-information" style="margin-top: 40px; text-align: center;">  
    </div>
    <!-- Student Information Div End-->
    
    <!-- Serch Book Button and Searchbar Start -->
    <div class="col-md-12 " id="book-search-container" style="margin-top: 40px; text-align: center;">
    </div>
    <!-- Serch Book Button and Searchbar End -->

    <!-- User Borrowed Books Start -->
    <div class="col-md-12 " id="student-borrowed-books-container" style="margin-top: 40px; text-align: center;">

    </div>
    <!-- User Borrowed Books End -->

     <!-- User Borrowed Books Start -->
    <div class="col-md-12 " id="student-transaction-history-container" style="margin-top: 40px; text-align: center;">

    </div>
    <!-- User Borrowed Books End -->

    
    <!-- Students Searches Modal Start-->
    <div id="studentsModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Search &nbsp;
                        <small>
                            [ <span id="currentPage"></span> - <span id="lastPage"></span> ]
                        </small>
                    </h4>
                </div>
                <div class="modal-body">
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <th>Student No.</th>
                                <th>Fullname</th>
                                <th>Department</th>
                                <th>Grade Level</th>
                                <th>Track</th>
                                <th>SELECT</th>
                            </thead>
                            <tbody id="modal-tbody">

                            </tbody>
                        </table>

                        <nav aria-label="Page navigation example">
                          <ul class="pagination justify-content-center m-b-0 m-t-0">
                            <li class="page-item">
                              <a href="javascript:void(0)" class="page-link" onclick="nextPage()" v-if="prevPage !== null">Previous</a>
                            </li>
                            <li class="page-item">
                              <a href="javascript:void(0)" class="page-link" onclick="nextPage()" v-if="nextPage !== null">Next</a>
                            </li>
                          </ul>
                        </nav>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Students Searches Modal End-->

    <!-- Book Searches Modal Start-->
    <div id="booksModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Search &nbsp;
                        <small>
                            [ <span id="bookCurrentPage"></span> - <span id="bookLastPage"></span> ]
                        </small>
                    </h4>
                </div>
                <div class="modal-body">
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <th>Accession No.</th>
                                <th>Title</th>
                                <th>Call No.</th>
                                <th>Code</th>
                                <th>SELECT</th>
                            </thead>
                            <tbody id="book-modal-tbody">

                            </tbody>
                        </table>

                        <nav aria-label="Page navigation example">
                          <ul class="pagination justify-content-center m-b-0 m-t-0">
                            <li class="page-item">
                              <a href="javascript:void(0)" class="page-link" onclick="requestPage(prevPage)" v-if="prevPage !== null">Previous</a>
                            </li>
                            <li class="page-item">
                              <a href="javascript:void(0)" class="page-link" onclick="requestPage(nextPage)" v-if="nextPage !== null">Next</a>
                            </li>
                          </ul>
                        </nav>
                    
                </div>
            </div>
        </div>
    </div>

</div>


{{-- FIELD EXTRA CSS  --}}
{{-- push things in the after_styles section --}}




    {{-- FIELD EXTRA JS --}}
    {{-- push things in the after_scripts section --}}

@push('crud_fields_scripts')
    <!-- no scripts -->
    <script type="text/javascript">

        function searchNow(){
            var searchbar = document.getElementById('searchbar').value;
            if(searchbar == null || searchbar === '') {
                alert("Please enter a keyword");
                return false;
            }
            $.ajax({
                type : 'get',
                data : {
                        search: searchbar,
                        _token:"{{csrf_token()}}"
                    },
                url: 'book-transaction/api/get/student',
                success : function(data){
                        $("#modal-tbody").empty();
                        $('#modal-tbody').append(data.html);
                        $('#studentsModal').modal('toggle');
                },error: function(data){
                     console.log("The request failed");
                },
            })
        }

        function selectStudent(idx) {
            $("#first-view").empty();
            $.ajax({
                type : 'get',
                data : {
                        user_id: idx,
                        _token:"{{csrf_token()}}"
                    },
                url: 'book-transaction/api/get/selected-student',
                success : function(data){
                    $('#studentsModal').modal('toggle');
                    $('#student-information').empty();
                    $('#book-search-container').empty();
                    $('#student-borrowed-books-container').empty();
                    $('#student-transaction-history-container').empty();
                    $('#student-information').append(data.htmlUserInfo);
                    $('#book-search-container').append(data.htmlBookSearchForm);
                    $('#student-borrowed-books-container').append(data.htmlUserBorrowedBooks);
                    $('#student-transaction-history-container').append(data.htmlUserTransactions);
                },error: function(data){
                     console.log("The request failed");
                },
            })
        }

        function searchBook(){
            var searchbar = document.getElementById('book-searchbar').value;
            if(searchbar == null || searchbar === '') {
                alert("Please enter a keyword");
                return false;
            }
            $.ajax({
                type : 'get',
                data : {
                        search: searchbar,
                        _token:"{{csrf_token()}}"
                    },
                url: 'book-transaction/api/get/book',
                success : function(data){
                        $("#book-modal-tbody").empty();
                        $('#book-modal-tbody').append(data.html);
                        $('#booksModal').modal('toggle');
                },error: function(data){
                     console.log("The request failed");
                },
            })
        }

        function borrowBook(idx) {
            console.log($('#studentID').val());
            $.ajax({
                type : 'get',
                data : {
                        book_id: idx,
                        _token:"{{csrf_token()}}"
                    },
                url: 'book-transaction/api/get/selected-student',
                success : function(data){
                    $('#studentsModal').modal('toggle');
                    $('#student-information').append(data.html);
                },error: function(data){
                     console.log("The request failed");
                },
            })
        }

    </script>
  
@endpush