<?php

namespace App\Http\Controllers\QuickBooks;

use Illuminate\Http\Request;

use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;

// FACADES
use QuickBooksOnline\API\Facades\Payment;


class PaymentController extends QuickBooks
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments    = $this->dataService->Query('SELECT * FROM Payment');
        $customers   = $this->dataService->Query('SELECT * FROM Customer');
        $error       = $this->dataService->getLastError();

        // $entityList = array('Customer','Vendor');
        // $date = strtotime("-10 day");
        // $changedSince = date("Y-m-d", $date); // 10 days ago

        // $cdcResponse = $this->dataService->CDC($entityList, $changedSince);

        dd($cdcResponse);

        if ($error) {
            echo "The Status code is: " . $error->getHttpStatusCode() . "<br>";
            echo "The Helper message is: " . $error->getOAuthHelperError() . "<br>";
            echo "The Response message is: " . $error->getResponseBody() . "<br>";
            exit();
        }
        return view('quickbooks.payment.list', compact('payments', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = $this->dataService->Query('SELECT * FROM Customer');
        $error     = $this->dataService->getLastError();

        if ($error) {
            echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
            echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
            echo "The Response message is: " . $error->getResponseBody() . "\n";
            exit();
        }

        return view('quickbooks.payment.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $theResourceObj = Payment::create([
          "TotalAmt" => (float)$request->TotalAmt,
          "CustomerRef" =>
          [
              "value" => (int)$request->CustomerRef_value
          ],
          // "Line" => [
          // [
          //     "Amount" => 100.00,
          //     "LinkedTxn" => [
          //     [
          //         "TxnId" => "210",
          //         "TxnType" => "Invoice"
          //     ]]
          // ]]
        ]);

        $resultingObj = $this->dataService->Add($theResourceObj);
        $error        = $this->dataService->getLastError();

        if ($error) {
            echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
            echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
            echo "The Response message is: " . $error->getResponseBody() . "\n";
        }
        else {
            \Alert::success("Successfully Adding Payment")->flash();
            return redirect()->back();
            // echo "Created Id={$resultingObj->Id}. Reconstructed response body:\n\n";
            // $xmlBody = XmlObjectSerializer::getPostXmlFromArbitraryEntity($resultingObj, $urlResource);
            // echo $xmlBody . "\n";
        }
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
}
