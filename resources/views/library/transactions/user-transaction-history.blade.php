<table class='table table-striped table-bordered' id="user-transaction-history-table">
    <thead>
        <th>Accession No.</th>
        <th>Title</th>
        <th>Date Borrowed</th>
        <th>Date Returned</th>
    </thead>
    <tbody id="borrowed-books-tbody">
        @if(count($UserTransactions)>0)
            @foreach($UserTransactions as $key => $UserTransaction)
                <tr>
                    <td style='vertical-align:middle'>{{ $UserTransaction->accession_number }}</td>
                    <td style='vertical-align:middle'>{{ $UserTransaction->title }}</td>
                    <td style='vertical-align:middle'>{{ $UserTransaction->date_borrowed }}</td>
                    <td style='vertical-align:middle'>{{ $UserTransaction->date_returned }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>