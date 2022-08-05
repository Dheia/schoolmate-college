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
                            <span v-if="selectedStudent != null">{{ selectedStudent.current_level }}</span>
                        </td>
                        
                        <td><b><small>Year:</small></b></td>
                        <td id="year">&nbsp; 
                            <!-- <span v-if="selectedStudent != null">{{ selectedStudent.school_year.schoolYear }}</span> -->
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-11 col-sm-10" style="padding: 0; margin: 0">
            <input v-model="searchbar" @keyup.enter="searchNow()" type="text" class="form-control" id="search" placeholder="Studentnumber">
        </div>
        <div class="col-md-1 col-sm-2"  style="padding: 0; margin: 0">
            <a href="#" @click="searchNow()" class="btn btn-primary btn-block form-control" style="border-radius: 0;">Search</a>
        </div>

        <div v-if="borrowedBooks == null" class="col-md-12 " id="first-view" style="margin-top: 40px;">
            <h4 class="text-center">SEARCH FOR STUDENT LIBRARY RECORD</h4> 
            <i class="fa fa-users fa-5x text-center" style="display: block; padding-bottom: 50px;"></i>
        </div>

        <!-- BORROWED BOOKS -->
        <div id="borrowed-books-info" class="col-md-12" style="padding: 0;">
            <div v-if="borrowedBooks !== null">
                 <h3>Borrowed Books</h3>
                <table class="table table-bordered table-striped" style="margin-top: 30px;">
                    <thead style="background-color: rgb(66, 40, 108); color: rgb(255, 255, 255);">
                        <th style="padding: 5px;">Accession No</th>
                        <th style="padding: 5px;">Title</th>
                        <th style="padding: 5px;">Fine</th>
                        <th style="padding: 5px;">Borrowed Date</th>
                        <th style="padding: 5px;">Due Date</th>
                        <th style="padding: 5px;">Status</th>
                        <th style="padding: 5px;">Action</th>
                    </thead>
                    <tbody>
                        <tr v-for="borrowedBook in borrowedBooks">
                            <td>{{ borrowedBook.accession_number }}</td>
                            <td>{{ borrowedBook.title }}</td>
                            <td>₱{{ borrowedBook.fine }}</td>
                            <td>{{ borrowedBook.date_borrowed }}</td>
                            <td>{{ borrowedBook.due_date }}</td>
                            <td>{{ borrowedBook.status }}</td>
                            <td>
                                <a href="javascript:void(0)"
                                    @click="returnModal(borrowedBook.id, borrowedBook.title, borrowedBook.fine)"
                                   :data-id="borrowedBook.id"
                                   :data-fine="borrowedBook.fine"
                                   :data-title="borrowedBook.title"
                                   class="btn btn-xs btn-primary">
                                   <i class="fa fa-arrow-left"></i> Return
                                </a>
                                <a v-if="borrowedBook.fine == 0" href="javascript:void(0)" 
                                   @click="renewModal(borrowedBook.id, borrowedBook.title, borrowedBook.fine)"
                                   :data-id="borrowedBook.id"
                                   :data-fine="borrowedBook.fine"
                                   :data-title="borrowedBook.title"
                                   class="btn btn-xs btn-success">
                                   <i class="fa fa-refresh"></i> Renew
                                </a>
                                <a v-if="borrowedBook.fine > 0" href="javascript:void(0)" 
                                   @click="paidModal(borrowedBook.id, borrowedBook.title, borrowedBook.fine)"
                                   :data-id="borrowedBook.id"
                                   :data-fine="borrowedBook.fine"
                                   :data-title="borrowedBook.title"
                                   class="btn btn-xs btn-info"
                                   style="background-color: rgb(66, 40, 108);">
                                   <i class="fa fa-money"></i> Pay
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- Return Book Modal -->
                <div class="modal fade" id="returnBooksModal" 
                  tabindex="-1" role="dialog" 
                  aria-labelledby="returnBooksModal">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"  id="returnBooksModalLabel">
                                {{return_book_title}}
                            </h4>
                            <button type="button" class="close" 
                            data-dismiss="modal" 
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        </div>
                        <form @submit.prevent="returnBook">
                            
                          <div class="modal-body">
                            <input v-if="selectedStudent != null" type="hidden" :id="return_book_id" :name="return_book_id" :value="return_book_id">
                            <input v-if="selectedStudent != null" type="hidden" :id="return_book_fine" :name="return_book_fine" :value="return_book_fine">
                            <p>Are you sure you want to return this book?</p>
                          </div>
                          <div class="modal-footer">
                            <span class="pull-right">
                                <button class="btn btn-primary">
                                    Return
                                </button>
                            </span>
                            <button type="button" 
                              class="btn btn-default" 
                              data-dismiss="modal">Cancel</button>
                          </div>
                        </form>
                      </div>
                    </div>
                </div>
                <!-- Paid Book Fine Modal -->
                <div class="modal fade" id="paidBookFineModal" 
                  tabindex="-1" role="dialog" 
                  aria-labelledby="paidBookFineModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title"  id="paidBookFineModalLabel">
                                    {{return_book_title}}
                                </h4>
                                <button type="button" class="close" 
                                data-dismiss="modal" 
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            </div>
                            <form @submit.prevent="paidFine">
                                <div class="modal-body">
                                    <input v-if="selectedStudent != null" type="hidden" :id="return_book_id" :name="return_book_id" :value="return_book_id">
                                    <p>Student fine for this book is ₱{{this.return_book_fine}}</p>
                                </div>
                                <div class="modal-footer">
                                    <span class="pull-right">
                                        <button class="btn btn-primary">
                                            Paid
                                        </button>
                                    </span>
                                    <button type="button" 
                                      class="btn btn-default" 
                                      data-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- RENEW BOOK MODAL -->
                <div class="modal fade" id="renewBooksModal" 
                  tabindex="-1" role="dialog" 
                  aria-labelledby="renewBooksModal">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"  id="renewBooksModalLabel">
                                {{renew_book_title}}
                            </h4>
                            <button type="button" class="close" 
                            data-dismiss="modal" 
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        </div>
                        <form @submit.prevent="renewBook">
                            <input v-if="selectedStudent != null" type="hidden" :id="renew_transaction_id" :name="renew_transaction_id" :value="renew_transaction_id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="renew_days">How many days to borrow?</label>
                                    <input class="form-control" type="number" id="renew_days" name="renew_days" placeholder="Enter Number of Days to Renew">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <span class="pull-right">
                                    <button class="btn btn-primary">
                                        Renew
                                    </button>
                                </span>
                                <button type="button" 
                                  class="btn btn-default" 
                                  data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-center" v-if="selectedStudent != null">
            <div class="col-md-11 col-sm-10" style="padding: 0; margin: 0">
                <input v-model="bookSearchbar" @keyup.enter="searchBookNow()" type="text" class="form-control" id="bookSearchbar" placeholder="Accession No.">
            </div>
            <div class="col-md-1 col-sm-2"  style="padding: 0; margin: 0">
                <a href="#" @click="searchBookNow()" class="btn btn-primary btn-block form-control" style="border-radius: 0;">Search</a>
            </div>
            <h4 class="col-md-12 col-sm-12 text-center">SEARCH FOR BOOK</h4> 
            <i class="fa fa-book fa-5x text-center" style="display: block; padding-bottom: 50px;"></i>
        </div>

        <div id="transaction-history" v-if="bookTransactions != null" class="col-md-12" style="padding: 0;">
            <!-- <tuition-fee></tuition-fee> -->
            <div>
              <h3>Transaction History</h3>
              <table class="table table-bordered table-striped" style="margin-top: 30px;">
                <thead style="background-color: rgb(66, 40, 108); color: rgb(255, 255, 255);">
                  <th style="padding: 5px;">Accession No</th>
                  <th style="padding: 5px;">Title</th>
                  <th style="padding: 5px;">Fine</th>
                  <th style="padding: 5px;">Borrowed Date</th>
                  <th style="padding: 5px;">Due Date</th>
                  <th style="padding: 5px;">Returned Date</th>
                  <th style="padding: 5px;">Status</th>
                  <th style="padding: 5px;">Action</th>
                </thead>
                <tbody>
                    <tr v-for="bookTransaction in bookTransactions">
                        <td>{{ bookTransaction.accession_number }}</td>
                        <td>{{ bookTransaction.title }}</td>
                        <td>₱{{ bookTransaction.fine }}</td>
                        <td>{{ bookTransaction.date_borrowed }}</td>
                        <td>{{ bookTransaction.due_date }}</td>
                        <td>{{ bookTransaction.date_returned }}</td>
                        <td>{{ bookTransaction.fine_status }}</td>
                        <td>
                            <a v-if="bookTransaction.fine > 0 && bookTransaction.paid_date == null" href="javascript:void(0)" 
                               @click="paidModal(bookTransaction.id, bookTransaction.title, bookTransaction.fine)"
                               :data-id="bookTransaction.id"
                               :data-fine="bookTransaction.fine"
                               :data-title="bookTransaction.title"
                               class="btn btn-xs btn-info"
                               style="background-color: rgb(66, 40, 108);">
                               <i class="fa fa-money"></i> Pay
                            </a>
                        </td>
                    </tr>
                </tbody>
              </table>
            </div>
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
                    <center>
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <th>Student No.</th>
                                <th>Fullname</th>
                                <th>Department</th>
                                <th>Grade Level</th>
                                <th>Track</th>
                                <th>SELECT</th>
                            </thead>
                            <tbody>
                                <tr v-for="student in searchItems" :id="'student-' + student.id">
                                    <td id='student-number'     style='vertical-align:middle'>{{ student.studentnumber }}</td>
                                    <td id='student-fullname'   style='vertical-align:middle'>{{ student.fullname }}</td>
                                    <td id='student-year'       style='vertical-align:middle'>{{ student.current_level }}</td>
                                    <td id='student-department'   style='vertical-align:middle'>{{ student.department_name }}</td>
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
        <!-- Books Modal -->
        <div id="booksModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Search &nbsp;
                            <small>
                                [ <span id="bookCurrentPage">{{ bookCurrentPage }}</span> - <span id="bookLastPage">{{ bookLastPage }}</span> ]
                            </small>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <center>
                            <table class='table table-striped table-bordered'>
                                <thead>
                                    <th>Accession No.</th>
                                    <th>Title</th>
                                    <th>Call No.</th>
                                    <th>Code</th>
                                    <th>SELECT</th>
                                </thead>
                                <tbody>
                                    <tr v-for="book in searchBooksItems" :id="'book-' + book.id">
                                        <td id='book-accession-number'     style='vertical-align:middle'>{{ book.accession_number }}</td>
                                        <td id='book-title'   style='vertical-align:middle'>{{ book.title }}</td>
                                        <td id='book-call-number'       style='vertical-align:middle'>{{ book.call_number }}</td>
                                        <td id='book-code'   style='vertical-align:middle'>{{ book.code }}</td>
                                        <td>
                                            <a href='#' @click="selectBook(book.id)" class='btn btn-primary btn-block'>Borrow</a>
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
        <div class="modal fade" id="borrowBooksModal" 
          tabindex="-1" role="dialog" 
          aria-labelledby="borrowBooksModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"  id="borrowBooksModalLabel" v-if="selectedBook != null">
                            {{selectedBook.title}}
                        </h4>
                        <button type="button" class="close" 
                        data-dismiss="modal" 
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    </div>
                    <form @submit="borrowBook">
                        <div class="modal-body">
                              <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 v-if="selectedBook != null">
                                            <strong>Accession No: </strong>{{selectedBook.accession_number}}
                                        </h4>
                                    </div>
                                    <div class="col-md-6">
                                        <h4 v-if="selectedBook != null">
                                            <strong>Call No: </strong>{{selectedBook.call_number}}
                                        </h4>
                                    </div>
                                </div>
                                <br>
                                  <input v-if="selectedStudent != null" type="hidden" :id="borrow_student_id" name="borrower_id" :value="selectedStudent.id">
                                  <input v-if="selectedBook != null" type="hidden" :id="borrow_book_id" name="borrower_book_id" :value="selectedBook.id">
                                  <div class="form-group">
                                      <label for="borrow_days">How many days to borrow?</label>
                                      <input class="form-control" type="number" id="borrow_days" name="borrow_days" placeholder="Enter Number of Days to Borrow">
                                  </div>
                              </div>
                        </div>
                        <div class="modal-footer">
                            <span class="pull-right">
                                <button class="btn btn-primary">
                                    Borrow
                                </button>
                            </span>
                            <button type="button" 
                              class="btn btn-default" 
                              data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</template>

<script>
    //import UserBookTransactions from './UserBookTransactions.vue';


    export default {
        data() {
            return {
                searchbar: null,
                bookSearchbar: null,
                searchItems: [],
                searchBooksItems: [],
                baseUrl: location.protocol + '//' + location.host,
                selectedStudent: null,  
                selectedBook: null,  
                borrowedBooks: null,
                bookTransactions: null,
                nextPage: null,
                prevPage: null,
                currentPage: null,
                lastPage: null,
                bookCurrentPage: null,
                bookLastPage: null,
                borrow_student_id: '',
                borrow_book_id: '',
                borrow_days: '',
                return_book_id: '',
                return_book_title: '',
                return_book_fine: '',
                return_student_id: '',
                renew_transaction_id: '',
                renew_book_title: '',
                renew_days: '',
                paid_transaction_id: '',
                fine: ''
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

                axios.get('/admin/library/book-transaction/api/get/student?search=' + this.searchbar)
                    .then(response => {
                        
                        this.searchItems = response.data.data;
                        this.nextPage    = response.data.next_page_url;
                        this.prevePage   = response.data.prev_page_url;
                        this.currentPage = response.data.current_page;
                        this.lastPage    = response.data.last_page;

                        $('#studentsModal').modal('toggle');
                    });
            },

            searchBookNow() {
                if(this.bookSearchbar == null || this.bookSearchbar === '') {
                    alert("Please enter a keyword");
                    return false;
                }

                axios.get('/admin/library/book-transaction/api/get/books?search=' + this.bookSearchbar)
                    .then(response => {
                        
                        this.searchBooksItems = response.data.data;
                        this.nextPage         = response.data.next_page_url;
                        this.prevePage        = response.data.prev_page_url;
                        this.currentPage      = response.data.current_page;
                        this.lastPage         = response.data.last_page;

                        $('#booksModal').modal('toggle');
                    });
            },

            selectStudent(idx) {
                this.selectedStudent = this.searchItems.find(item => item.id == idx);
                $('#studentsModal').modal('toggle');
                this.searchbar = null,
                // this.getTuitionList(this.selectedStudent.studentnumber);
                this.getBorrowedBooks(this.selectedStudent.studentnumber);
            },

            selectBook(idx) {
                this.selectedBook = this.searchBooksItems.find(item => item.id == idx);
                $('#booksModal').modal('toggle');
                this.bookSearchbar = null,
                // this.getTuitionList(this.selectedStudent.studentnumber);
                $('#borrowBooksModal').modal('toggle');
            },

            borrowBook(){
                axios.post('/admin/library/book-transaction/api/borrow/book', {
                    borrow_student_id: this.selectedStudent.studentnumber,
                    borrow_book_id: this.selectedBook.id,
                    borrow_days: $('#borrow_days').val()
                }).then(function (response) {
                    if(response.data == '1'){
                        $('#borrowBooksModal').modal('toggle');
                    }
                });
                this.getBorrowedBooks(this.selectedStudent.studentnumber);

            },
            returnBook() {
                var response ='';
                axios.post('/admin/library/book-transaction/api/return/book', {
                    return_book_id: this.return_book_id,
                    return_book_fine: this.return_book_fine
                }).then(function (response) {
                    $('#returnBooksModal').modal('toggle');
                }).catch(function (error) {
                    alert(error);
                });
                 this.getBorrowedBooks(this.selectedStudent.studentnumber);
            },

            getBorrowedBooks (studentnumber) {
                axios.get('/admin/library/book-transaction/api/get/borrowed-books/' + studentnumber)
                        .then(response => {
                            this.borrowedBooks = response.data;
                        });
                this.getUserBookTransactions(this.selectedStudent.studentnumber);
            },

            getUserBookTransactions (studentnumber) {
                axios.get('/admin/library/book-transaction/api/get/book-transactions/' + studentnumber)
                        .then(response => {
                            this.bookTransactions = response.data;
                        });
            },
            returnModal: function($transaction_id, $book_title, $book_fine){
                this.return_book_id     =   $transaction_id;
                this.return_book_title  =   $book_title;
                this.return_book_fine   =   $book_fine;
                $('#returnBooksModal').modal('toggle');
            },
            renewModal($renew_transaction_id, $book_title, $book_fine){
                this.renew_transaction_id   =   $renew_transaction_id;
                this.renew_book_title       =   $book_title;
                $('#renewBooksModal').modal('toggle');  
            },
             paidModal($paid_transaction_id, $book_title, $book_fine){
                this.paid_transaction_id    =   $paid_transaction_id;
                this.return_book_title      =   $book_title;
                this.return_book_fine       =   $book_fine;
                $('#paidBookFineModal').modal('toggle');
            },
            
            renewBook() {
                axios.post('/admin/library/book-transaction/api/renew/book', {
                    renew_transaction_id: this.renew_transaction_id,
                    renew_days: $('#renew_days').val()
                }).then(function (response) {
                    if(response.data == '1'){
                        $('#renewBooksModal').modal('toggle');
                    }
                });
                this.getBorrowedBooks(this.selectedStudent.studentnumber);
            },
            paidFine() {
                axios.post('/admin/library/book-transaction/api/paid-fine/book', {
                    paid_transaction_id: this.paid_transaction_id,
                    fine: this.return_book_fine
                }).then(function (response) {
                    if(response.data == '1'){
                        $('#paidBookFineModal').modal('toggle');
                    }
                });
                this.getBorrowedBooks(this.selectedStudent.studentnumber);
            }
        },

        created() {
            console.log("Reading");    
            jQuery(function ($) {
                $('form').submit(false);
            }) 
        },
        components: {
            //UserBookTransactions
        }
    };
</script>


