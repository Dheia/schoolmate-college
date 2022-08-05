<?php

namespace App\Http\Controllers\Quickbooks;

use Illuminate\Http\Request;

use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;

use QuickBooksOnline\API\Facades\Account;
use App\ChartOfAccount;
use Carbon\Carbon;

class ChartOfAccountController extends Quickbooks
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {     
        
        $chartOfAccounts = $this->dataService->Query('SELECT * FROM Account');
        $chartOfAccounts = $chartOfAccounts == null ? [] : $chartOfAccounts;
        
        $error = $this->dataService->getLastError();
        if ($error) {
            echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
            echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
            echo "The Response message is: " . $error->getResponseBody() . "\n";
            exit();
        }
        // return response()->json($chartOfAccounts);
        return view('quickbooks.chartOfAccounts.list', compact('chartOfAccounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action         = "CREATE";
        $accountTypes   = ChartOfAccount::accountType();
        $accounts       = $this->dataService->Query('SELECT * FROM Account');
        $accounts       = collect($accounts)->groupBy('Classification');
        // return response()->json($accounts);
        return view("quickbooks.chartOfAccounts.create", compact('action', 'accountTypes', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $isSubAccount = $request->IsSubAccount == "on" ?  true : false;
        $parent_id    = null;
        $asOf         = $request->AsOf ?? Carbon::today()->format('Y-m-d');

        $item = [
            "Name"               => $request->Name,
            "AccountType"        => $request->AccountType,
            "AccountSubType"     => $request->AccountSubType,
            "OpeningBalance"     => $request->Balance ?? 0,
            "OpeningBalanceDate" => $asOf,
            "SubAccount"         => $isSubAccount,
            "Description"        => $request->Description,
        ];

        if($isSubAccount) {
            $parent            = $this->dataService->Query("SELECT * FROM Account WHERE Name = '" . $request->SubAccount . "'");
            $parent_id         = $parent[0]->Id;
            $item['ParentRef'] = $parent_id;
        }

        $theResourceObj = Account::create($item);
        $resultingObj   = $this->dataService->Add($theResourceObj);
        // dd($theResourceObj);
        $error          = $this->dataService->getLastError();

        if ($error) {
            \Alert::warning("Error: " . $error->getResponseBody())->flash();
            return redirect()->back()->withInput();
        }

        \Alert::warning("Succesufully Added " . $request->Name)->flash();
        return redirect()->to($request->getPathInfo());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $account = $this->dataService->FindById('Account', $id);
        $invoices = $this->dataService->Query("SELECT * FROM Invoice");
        // dd($account, $invoices);
        return response()->json($invoices);
        $account = $account == null ? [] : $account;
        $error   = $this->dataService->getLastError();

        if ($error) {
            \Alert::warning("Error: " . $error->getResponseBody())->flash();
            return redirect()->back()->withInput();
        }
        return view('quickbooks.chartOfAccounts.show');
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
