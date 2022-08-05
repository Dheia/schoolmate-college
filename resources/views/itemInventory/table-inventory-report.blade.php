<table class="table" border="0">
  <thead>
    <tr>
      <td>
        <div class="header-space">&nbsp;</div>
      </td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        <div class="content">
          <table class="table" id="table-content" cellspacing="0" border="0" style="border: 0;">
            <thead>
              <th class="text-center">Item ID</th>
              <th class="text-center">Item Code</th>
              <th class="text-center">Start Quantity</th>
              <th class="text-center">End Quantity</th>
              <th class="text-center">Total</th>
            </thead>
            <tbody>

              @foreach($reports as $report)
                <tr>
                  <td class="text-center" style="vertical-align: middle;">{{ $report->id }}</td>
                  <td class="text-center" style="vertical-align: middle;">{{ $report->item_id }}</td>
                  <td class="text-center" style="vertical-align: middle;">{{ $report->start_quantity }}</td>
                  <td class="text-center" style="vertical-align: middle;">{{ $report->end_quantity }}</td>
                  <td class="text-center" style="vertical-align: middle;">{{ $report->start_quantity - $report->end_quantity }}</td>
                </tr>
              @endforeach

            </tbody>
            <tfoot>
              <tr style="display: none;">
                <td></td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <td>
      <div class="footer-space">&nbsp;</div>
      </td>
    </tr>
  </tfoot>
</table>