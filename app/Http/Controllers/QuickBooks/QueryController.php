<?php

namespace App\Http\Controllers\QuickBooks;

use Illuminate\Http\Request;

use QuickBooksOnline\API\Facades\Invoice;

class QueryController extends QuickBooksOnline
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('quickbooks.query.list')->withQuery(null)->withResponse(null);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->initialize();

        $response = $this->dataService()->Query( ( $request->input('query') ) );
        $error    = $this->dataService()->getLastError();

        if ($error) 
        {
            $msg = "The Status code is: "       . $error->getHttpStatusCode()   . "<br>" .
                    "The Helper message is: "   . $error->getOAuthHelperError() . "<br>" . 
                    "The Response message is: " . $error->getResponseBody();
            \Alert::warning($msg)->flash();
            return view('quickbooks.query.list')->withQuery($request->input('query'))->withResponse(null);
        }

        return view('quickbooks.query.list')->withQuery($request->input('query'))->withResponse($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function invoiceUpdate()
    {
        $this->initialize();

        $theResourceObj = $this->dataService()->Query("SELECT * FROM Invoice");

        if($theResourceObj == null)
        {
            return "EMPTY";
        }

        $error = $this->dataService()->getLastError();
         if ($error) {
            dd($error);
        }
        // dd($theResourceObj);
        // foreach ($theResourceObj as $oneInvoice) 
        // {
            // $theResourceObj[0]->SyncToken  = "1";
            // $theResourceObj[0]->Id  = $theResourceObj[0]->Id;
            // $theResourceObj[0]->sparse  = "false";
            $theResourceObj[0]->DueDate = "2020-03-30";

            $updateInvoice = Invoice::update($theResourceObj[0], [
                //If you are going to do a full Update, set sparse to false
                'sparse' => 'true',
                // "SyncToken" => "0", 
                // "Id" => $theResourceObj[0]->Id, 
                "DueDate" => "2020-06-30"
            ]);
            // dd($updateInvoice);
            $resultingCustomerUpdatedObj = $this->dataService->Update($updateInvoice);
        // }

            if ($error) {
                dd($error);
            }

        dd($theResourceObj[0], $resultingCustomerUpdatedObj);
    }

}
