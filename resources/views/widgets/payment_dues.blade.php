

<div class="row"> 
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="info-box shadow">
        <div class="box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Payment Dues for S.Y. {{ $school_year ?? null }}</h3>
          </div>
          
          <div class="box-body">
            <table class="table table-responsive-sm table-striped" id="payment_dues_table" width="100%">
              <thead>
                <tr>
                  <th scope="col">Studentnumber</th>
                  <th scope="col">Fullname</th>
                  <th scope="col">Grade</th>
                  <th scope="col">Term</th>
                  <th scope="col">Amount Due</th>
                </tr>
              </thead>
              <tbody id="payment_dues_body">
                @foreach($enrolment as $enrolment)
                    @if($enrolment->remaining_balance > 0)
                        <tr>
                            <th scope="row">{{ $enrolment->studentnumber }}</th>
                            <td>{{ $enrolment->full_name }}</td>
                            <td>{{ $enrolment->level_name }} {{ $enrolment->track ? '- ' .$enrolment->track->code : '' }}</td>
                            <td>{{ $enrolment->term_type ? $enrolment->term_type . ' Term' : '-'  }}</td>
                            <td>
                            <!-- Peso Sign (&#8369;) -->
                            &#8369; {{ number_format((float)$enrolment->remaining_balance, 2) }}
                            </td>
                        </tr>
                    @endif
                @endforeach
              </tbody>
            </table>

            {{-- <div id="datatable_button_stack" class="container hidden-xs"></div> --}}
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    @if(backpack_auth()->user()->hasRole('Administrator') || backpack_auth()->user()->hasRole('Accounting'))
      $(document).ready(function () {
        $('#payment_dues_table').DataTable({
          aaSorting: [],
          responsive: true,
          scrollX: true,
          dom: 'Blfrtip',
          select: true,
          dropup: true,
          colReorder: true,
          buttons: [
            {
                extend: 'collection',
                text: '<i class="fa fa-download"></i> Export',
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    'pdf',
                    'print'
                ]
            }
          ]
        });

        // $(".dt-buttons").appendTo($('#payment_dues_table_wrapper' ));
        // $('.dt-buttons').css('display', 'inline-block');
        // $('.dt-buttons').css('left', '50%');
      });
    @endif
  </script>

