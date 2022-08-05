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
          <table class="table" cellspacing="0" border="0" style="border: 0;">
            <thead>
              <th class="text-center">INVOICE NO.</th>
              <th class="text-center">TYPE</th>
              <th class="text-center">NAME</th>
              <th class="text-center">ITEMS</th>
              <th class="text-center">TOTAL</th>
            </thead>
            <tbody>
              <?php 
                $grandTotal = 0;
              ?>
              @foreach($reports as $report)
                {{-- {{dd($report)}} --}}
                <?php $grandTotal += $report->total; ?>
                <tr>
                  <td class="text-center" style="vertical-align: middle;">{{ $report->invoice_no }}</td>
                  <td class="text-center" style="vertical-align: middle;">{{ $report->user->user_type }}</td>
                  <td class="text-center" style="vertical-align: middle;">{{ $report->user->full_name }}</td>
                  <td style="vertical-align: middle;">
                    {{-- {{ $report->items }} --}}
                    <ul class="list-group" style="margin: 0;">
                      @foreach(json_decode($report->items) as $item)
                        <li class="list-group-item" style="display: flex; justify-content: space-between; flex-wrap: nowrap; flex-direction: row;">
                          <span style="flex-grow:1; flex-basis: 0;">{{ \App\Models\ItemInventory::findOrFail($item->item_id)->name }}</span>
                          <span class="text-center" style="flex-grow:1; flex-basis: 0;">x{{ $item->quantity }}</span>
                          <span class="text-right" style="flex-grow:1; flex-basis: 0;">P{{ $item->price }}</span>
                          {{-- <span class="text-center" style="flex-grow:1; flex-basis: 0;">{{ $item->quantity * $item->price }}</span> --}}
                        </li>
                      @endforeach
                    </ul>
                  </td>
                  <td class="text-center" style="vertical-align: middle;">Php {{ number_format((float)$report->total,2) }}</td>
                </tr>
              @endforeach
              @if(count($reports) > 0)
                <tr>
                  <td colspan="2" style="vertical-align: middle;" class="text-right"><b>GRAND TOTAL:</b></td>
                  <td class="text-center" style="vertical-align: middle;"><b>Php {{ number_format((float)$grandTotal,2) }}</b></td>
                </tr>
              @endif
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