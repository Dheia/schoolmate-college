<?php

namespace App\Http\Controllers\QuickBooks;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use QuickBooksOnline\API\Data\IPPPaymentMethod;
use QuickBooksOnline\API\QueryFilter\QueryMessage;

class PaymentMethodController extends QuickBooks
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paymentMethods = $this->dataService->Query("SELECT * FROM PaymentMethod");
        return view('quickbooks.paymentMethod.list', compact('paymentMethods'));
    }   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('quickBooks.paymentMethod.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $paymentObj         = new IPPPaymentMethod();
        $paymentObj->Name   = $request->Name;
        $paymentObj->Active = true;
        $paymentObj->Type   = $request->IsCreditCard ? "CREDIT_CARD" : "NON_CREDIT_CARD";

        $resultingObj       = $this->dataService->Add($paymentObj);
        $error = $this->dataService->getLastError();
        if ($error) {
            \Alert::warning("Error: " . $error->getResponseBody());
            return redirect()->back();
        }
        else {
            \Alert::warning("Succesufully Added " . $request->Name);
            return redirect()->back();
        }
       
        \Alert::warning("Something Went Wrong, Please Try Again...");
        return redirect()->back();
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
        $paymentMethod = $this->dataService->FindById('PaymentMethod', $id);
        return view('quickBooks.paymentMethod.edit', compact('paymentMethod'));
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
        // dd($request->Name);
        $paymentMethod         = $this->dataService->FindById('PaymentMethod', $id);
        $paymentMethod->Name   = $request->Name;
        $paymentMethod->Active = true;
        $paymentMethod->Type   = $request->IsCreditCard == "CREDIT_CARD" ? "CREDIT_CARD" : "NON_CREDIT_CARD";

        $resultingObj          = $this->dataService->Update($paymentMethod);

        $error = $this->dataService->getLastError();
        if ($error) {
            \Alert::warning("Error: " . $error->getResponseBody());
            return redirect()->back();
        }
        else {
            \Alert::warning("Succesufully Added " . $request->Name);
            return redirect()->back();
        }
       
        \Alert::warning("Something Went Wrong, Please Try Again...");
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        return redirect()->back();

        $resourceObj = $this->dataService->FindById('PaymentMethod', $id);
        $resultingObj = $this->dataService->Delete($resourceObj);

        $error = $this->dataService->getLastError();

        if ($error) {
            \Alert::warning("Error: " . $error->getResponseBody());
            return redirect()->back();
        }
        else {
            \Alert::warning("Succesufully Deleted " . $request->Name);
            return redirect()->back();
        }
       
        \Alert::warning("Something Went Wrong, Please Try Again...");
        return redirect()->back();
    }
}
