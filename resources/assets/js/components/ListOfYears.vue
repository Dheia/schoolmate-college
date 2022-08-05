<template>
    <div>
      <table class="table table-bordered table-striped" style="margin-top: 30px;">
        <thead style="background-color: #3c8dbc; color: rgb(255, 255, 255);">
          <th style="padding: 5px;">Tuition Form</th>
          <th style="padding: 5px;">Year</th>
          <th style="padding: 5px;">Department</th>
          <th style="padding: 5px;">Grade Level</th>
          <th style="padding: 5px;">Track</th>
          <th style="padding: 5px;">Term</th>
          <th style="padding: 5px;">Payment Method</th>
          <th style="padding: 5px;">Invoiced</th>
          <th style="padding: 5px;">Remaining Balance</th>
          <th style="padding: 5px;">Action</th>
        </thead>
        <tbody>
            <tr v-for="year in listYears">
              <td>{{ year.tuition.form_name }}</td>
              <td>{{ year.school_year.schoolYear }}</td>
              <td>{{ year.department_name }}</td>
              <td>{{ year.level_name }}</td>
              <td>{{ year.track_name }}</td>
              <td>{{ year.term_type }}</td>
              <td>{{ year.commitment_payment.name }}</td>
              <td v-if="year.invoice_no !== null || year.invoiced">Yes</td>
              <td v-else="year.invoice_no == null || !year.invoiced">No</td>
              <td>P{{ year.remaining_balance | formatNumber }}</td>
              <td>
                <a href="javascript:void(0)" 
                   @click="openAccount(year)"
                   class="btn btn-xs btn-primary">
                   <i class="fa fa-external-link"></i> Open
                </a>
              </td>
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
      openAccount(year) {
         window.open(this.baseUrl + '/admin/student-account/' + year.id, "_blank"); 
      }
    },
    props: ['listYears']
  }
</script>
