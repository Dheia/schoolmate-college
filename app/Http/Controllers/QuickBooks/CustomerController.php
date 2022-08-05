<?php

namespace App\Http\Controllers\QuickBooks;

use Illuminate\Http\Request;

use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;

use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Customer;

use App\Http\Controllers\Quickbooks\Paginator;

class CustomerController extends QuickBooks
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = $this->dataService->Query('SELECT * FROM Customer');
        $customers = $customers == null ? [] : $customers;

        $statement = "SELECT COUNT" . "(" . "*" . ")" . "FROM CUSTOMER";
        $numberOfCustomers = $this->dataService->Query($statement);
        // dd($numberOfCustomers);
        // return response()->json($customers);

        $totalItems   = $numberOfCustomers;
        $itemsPerPage = 10;
        $currentPage  = 1;
        $urlPattern   = '/admin/quickbooks/customer/page/(:num)';

        $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);

        $items = [
            "prev_url"      => $paginator->getPrevUrl(),
            "total_items"   => $paginator->getTotalItems(),
            "next_url"      => $paginator->getNextUrl(),
            "pages"         => $paginator->getPages()
        ];
        // dd($customers);
        $error = $this->dataService->getLastError();
        
        // $entityList = array('Customer','Vendor');
        // $date = strtotime("-10 day");
        // $changedSince = date("Y-m-d", $date); // 10 days ago
        // $cdcResponse = $this->dataService->CDC($entityList, $changedSince);

        if ($error) {
            echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
            echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
            echo "The Response message is: " . $error->getResponseBody() . "\n";
            exit();
        }
        
        return view('quickbooks.customer.list', compact('customers', 'numberOfCustomers'));
    }

    public function requestPage($page)
    {
        $customers = $this->dataService->Query('SELECT * FROM Customer');
        $customers = $customers == null ? [] : $customers;

        $statement = "SELECT COUNT" . "(" . "*" . ")" . "FROM CUSTOMER";
        $numberOfCustomers = $this->dataService->Query($statement);
        // dd($numberOfCustomers);
        // return response()->json($customers);

        $totalItems   = $numberOfCustomers;
        $itemsPerPage = 10;
        $currentPage  = $page;
        $urlPattern   = '/admin/quickbooks/customer/page/(:num)';

        $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);

        $items = [
            "prev_url"      => $paginator->getPrevUrl(),
            "total_items"   => $paginator->getTotalItems(),
            "next_url"      => $paginator->getNextUrl(),
            "pages"         => $paginator->getPages()
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = "CREATE";

        return view("quickbooks.customer.create", compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $customer = $this->dataService->FindAll("Customer");
        // $theResourceObj = Customer::update($customer, ["UserId" => $id]);
        $customer = $this->dataService->FindById("Customer", $id);
        // $resultingObj = $this->dataService->Update($theResourceObj);
        return response()->json($customer);
        // return $customer;
        


        // $entityList = array('Customer','Vendor');
        // $date = strtotime("-10 day");
        // $changedSince = date("Y-m-d", $date); // 10 days ago

        // $cdcResponse = $this->dataService->CDC($entityList, $changedSince);

        // $invoices  = $this->dataService->Query("SELECT * FROM Invoice WHERE CustomerRef = '$id'");
        // $payments  = $this->dataService->Query("SELECT * FROM Payment WHERE CustomerRef = '$id'");
        

        return view('quickbooks.customer.read');
        // return view('quickbooks.customer.read', compact('customer', 'invoices', 'payments'));
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