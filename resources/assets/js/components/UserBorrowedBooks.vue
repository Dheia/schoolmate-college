<template>
    <div>
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
            <tr v-for="borrowedBook in userBorrowedBooks">
              <td>{{ borrowedBook.accession_number }}</td>
              <td>{{ borrowedBook.title }}</td>
              <td>{{ borrowedBook.accession_number }}</td>
              <td>{{ borrowedBook.date_borrowed }}</td>
              <td>{{ borrowedBook.due_date }}</td>
              <td>{{ borrowedBook.status }}</td>
              <td>
                <a href="javascript:void(0)"
                    @click="returnModal(borrowedBook.id, borrowedBook.studentnumber)"
                   :data-id="borrowedBook.book_id"
                   :data-title="borrowedBook.title"
                   class="btn btn-xs btn-primary">
                   <i class="fa fa-arrow-left"></i> Return
                </a>
                <a href="javascript:void(0)" 
                   @click="returnModal(borrowedBook.accession_number, borrowedBook.title, borrowedBook.book_id)"
                   class="btn btn-xs btn-primary">
                   <i class="fa fa-refresh"></i> Renew
                </a>
              </td>
            </tr>
        </tbody>
      </table>
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
            <form @submit="returnBook">
              <div class="modal-body">
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
    </div>
</template>
<script>
  export default {
    data() {
      return {
        baseUrl: location.protocol + '//' + location.host,
        book_transaction_id: null,
        studentnumber: null,
      }
    },
    computed: {

    },
    methods: {
      returnModal($transaction_id, $studentnumber){
        $('#returnBooksModal').modal('toggle');
        this.book_transaction_id = $transaction_id;
        this.studentnumber = $studentnumber;
      },
      returnBook() {
         window.open(this.baseUrl + '/admin/student-accounts/create/view/account/' + year.id, "_blank"); 
      }
    },
    props: ['userBorrowedBooks']
  }
</script>
