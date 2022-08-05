<template>
    <div>
      <h3>Transaction History</h3>
      <table class="table table-bordered table-striped" style="margin-top: 30px;">
        <thead style="background-color: rgb(66, 40, 108); color: rgb(255, 255, 255);">
          <th style="padding: 5px;">Accession No</th>
          <th style="padding: 5px;">Title</th>
          <th style="padding: 5px;">Fine</th>
          <th style="padding: 5px;">Date Borrowed</th>
          <th style="padding: 5px;">Due Date</th>
          <th style="padding: 5px;">Date Returned</th>
          <th style="padding: 5px;">Status</th>
        </thead>
        <tbody>
            <tr v-for="bookTransaction in bookTransactions">
              <td>{{ bookTransaction.accession_number }}</td>
              <td>{{ bookTransaction.title }}</td>
              <td>{{ bookTransaction.fine }}</td>
              <td>{{ bookTransaction.date_borrowed }}</td>
              <td>{{ bookTransaction.due_date }}</td>
              <td>{{ bookTransaction.date_returned }}</td>
              <td>{{ bookTransaction.status }}</td>
              
            </tr>
        </tbody>
      </table>
    </div>
</template>
<script>
  export default {
    data() {
      return {
        baseUrl: location.protocol + '//' + location.host,
      }
    },
    computed: {

    },
    methods: {
      // openAccount(year) {
      //    window.open(this.baseUrl + '/admin/student-accounts/create/view/account/' + year.id, "_blank"); 
      // }
    },
    props: ['bookTransactions']
  }
</script>
