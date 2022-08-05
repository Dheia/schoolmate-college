<?php

namespace App\Http\Controllers\Quickbooks;

use App\Http\Requests\Quickbooks\ProductsAndServicesRequest as StoreRequest;

use QuickBooksOnline\API\Facades\Item;

class ProductsAndServicesController extends Quickbooks
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        try {

            $items = $this->dataService->Query('SELECT * FROM Item');
            $error = $this->dataService->getLastError();

            if ($error) {
                $msg = "The Status code is: "       . $error->getHttpStatusCode()   . "<br>" .
                        "The Helper message is: "   . $error->getOAuthHelperError() . "<br>" . 
                        "The Response message is: " . $error->getResponseBody();
                \Alert::warning()->flash($msg);
                return redirect()->back();
            }
            
            return view('quickbooks.productsAndServices.list', compact('items'));

        } catch(\Exception $e) {
            
            \Alert::warning()->flash($e->message);
            return redirect()->back();
        
        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
             if($request->Type == 'Inventory')    { return self::storeInventory($request); }
        else if($request->Type == 'NonInventory') { return self::storeNonInventory($request); }
        else if($request->Type == 'Service')      { return self::storeService($request); }
        else if($request->Type == 'Bundle')       { return self::storeBundle($request); }
        else 
        {
            \Alert::warning("Invalid URL Parameters..")->flash();
            return redirect()->to('admin/quickbooks/products-and-services');
        }
    }

    public function storeInventory ($request)
    {
        $IncomeAccountRef  = json_decode($request->IncomeAccountRef);
        $ExpenseAccountRef = json_decode($request->ExpenseAccountRef);
        $AssetAccountRef   = json_decode($request->AssetAccountRef);
        
        $theResourceObj = Item::create([
            "Name"              => $request->Name,
            "Sku"               => $request->Sku,
            "UnitPrice"         => $request->UnitPrice,
            "IncomeAccountRef"  => (array)$IncomeAccountRef,
            "ExpenseAccountRef" => (array)$ExpenseAccountRef,
            "AssetAccountRef"   => (array)$AssetAccountRef,
            "Type"              => $request->Type,
            "TrackQtyOnHand"    => true,
            "QtyOnHand"         => (int)$request->QtyOnHand,
            "InvStartDate"      => $request->InvStartDate
        ]);

        $resultingObj = $this->dataService->Add($theResourceObj);
        $error = $this->dataService->getLastError();
        if ($error) {
            $msg = "<b>The Status code is:</b> "      . $error->getHttpStatusCode() . "<br>" . 
                   "<b>The Helper message is:</b> "   . $error->getOAuthHelperError() . "<br>" . 
                   "<b>The Response message is:</b> " . $error->getResponseBody() . "<br>";

            \Alert::warning($msg)->flash();
            return redirect()->back();
        }
        else {    
            \Alert::success('Successfully Added ' . $request->Name)->flash();
            return redirect()->to('admin/quickbooks/products-and-services');
        }
    }

    public function storeNonInventory ($request)
    {

        $items = [
            "Name"      => $request->Name,
            "Type"      => "Service",
            "Sku"       => $request->Sku
        ];

        if($request->SubItem || $request->SubItem === "on" && isset($request->ParentRef) === true && $request->ParentRef !== null) 
        {
            try {

                $ParentRef                   = json_decode($request->ParentRef);
                $items['ParentRef']['name']  = $ParentRef->name; 
                $items['ParentRef']['value'] = (int)$ParentRef->value; 
                $items['SubItem']            = true;

            } catch (\Exception $e) {

                \Alert::warning("Please Select Sub-Product/Service")->flash();
                return redirect()->back()->withInput($request->input());

            }
        }

        if ($request->IsSales == "on") 
        {
            try {
                
                $IncomeAccountRef                   = json_decode($request->IncomeAccountRef);
                $items['IncomeAccountRef']['name']  = $IncomeAccountRef->name;
                $items['IncomeAccountRef']['value'] = (int)$IncomeAccountRef->value;
                $items['Description']               = $request->Description;
                $items['UnitPrice']                 = $request->UnitPrice;

            } catch (\Exception $e) {
                
                \Alert::warning("Invalid Income Account")->flash();
                return redirect()->back();
            
            }
        }

        if ($request->IsPurchasing == "on") 
        {
            try {
                
                $ExpenseAccountRef                   = json_decode($request->ExpenseAccountRef);
                $items['ExpenseAccountRef']['name']  = $ExpenseAccountRef->name;
                $items['ExpenseAccountRef']['value'] = (int)$ExpenseAccountRef->value;
                $items['PurchaseDesc']               = $request->PurchaseDesc;
                $items['PurchaseCost']               = $request->PurchaseCost;

            } catch (\Exception $e) {
                
                \Alert::warning("Invalid Expense Account")->flash();
                return redirect()->back();
            
            }
        }

        $theResourceObj = Item::create($items);
        $resultingObj   = $this->dataService->Add($theResourceObj);
        $error          = $this->dataService->getLastError();

        if ($error) {

           \Alert::warning($error->getResponseBody())->flash();
            return redirect()->back()->withInput($request->input());

        }
        else {    

            \Alert::success('Successfully Added ' . $request->Name)->flash();
            return redirect()->to('admin/quickbooks/products-and-services');

        }
    }

    public function storeService ($request)
    {

        $items = [
            "Name"      => $request->Name,
            "Type"      => "Service",
            "Sku"       => $request->Sku
        ];

        if($request->SubItem || $request->SubItem === "on" && isset($request->ParentRef) === true && $request->ParentRef !== null) 
        {
            try {

                $ParentRef                   = json_decode($request->ParentRef);
                $items['ParentRef']['name']  = $ParentRef->name; 
                $items['ParentRef']['value'] = (int)$ParentRef->value; 
                $items['SubItem']            = true;

            } catch (\Exception $e) {

                \Alert::warning("Please Select Sub-Product/Service")->flash();
                return redirect()->back()->withInput($request->input());

            }
        }

        if ($request->IsSales == "on") 
        {
            try {
                
                $IncomeAccountRef                   = json_decode($request->IncomeAccountRef);
                $items['IncomeAccountRef']['name']  = $IncomeAccountRef->name;
                $items['IncomeAccountRef']['value'] = (int)$IncomeAccountRef->value;
                $items['Description']               = $request->Description;
                $items['UnitPrice']                 = $request->UnitPrice;

            } catch (\Exception $e) {
                
                \Alert::warning("Invalid Income Account")->flash();
                return redirect()->back();
            
            }
        }

        if ($request->IsPurchasing == "on") 
        {
            try {
                
                $ExpenseAccountRef                   = json_decode($request->ExpenseAccountRef);
                $items['ExpenseAccountRef']['name']  = $ExpenseAccountRef->name;
                $items['ExpenseAccountRef']['value'] = (int)$ExpenseAccountRef->value;
                $items['PurchaseDesc']               = $request->PurchaseDesc;
                $items['PurchaseCost']               = $request->PurchaseCost;

            } catch (\Exception $e) {
                
                \Alert::warning("Invalid Expense Account")->flash();
                return redirect()->back();
            
            }
        }

        $theResourceObj = Item::create($items);
        $resultingObj   = $this->dataService->Add($theResourceObj);
        $error          = $this->dataService->getLastError();

        if ($error) {

           \Alert::warning($error->getResponseBody())->flash();
            return redirect()->back()->withInput($request->input());

        }
        else {    

            \Alert::success('Successfully Added ' . $request->Name)->flash();
            return redirect()->to('admin/quickbooks/products-and-services');

        }
    } 

    public function storeBundle ($request)
    {
        $items = [
            "Name"      => $request->Name,
            "Type"      => "Product",
            "Sku"       => $request->Sku
        ];

        $lines = json_decode($request->product_service);
        if(count($lines) > 0 && $lines !== null) {
            foreach ($lines as $key => $line) {
                $productService = json_decode($line->product_service);
                $items["ItemGroupDetail"]["ItemGroupLine"][] = ["Qty" => $line->quantity, "ItemRef" => ["name" => $productService->name, "value" => $productService->value]];
            }
        }

        // dd($items);

        if ($request->IsSubProductService == "on") 
        {
            $items['PrintGroupedItems'] = true;
        }

        $theResourceObj = Item::create($items);
        $resultingObj   = $this->dataService->Add($theResourceObj);
        $error          = $this->dataService->getLastError();

        if ($error) {

           \Alert::warning($error->getResponseBody())->flash();
            return redirect()->back()->withInput($request->input());

        }
        else {    

            \Alert::success('Successfully Added ' . $request->Name)->flash();
            return redirect()->to('admin/quickbooks/products-and-services');

        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        $array = ['inventory', 'non-inventory', 'service', 'bundle'];

        $isMatch = array_where($array, function ($value, $key) use ($name) {
            return $name === $value;
        });

        if(!$isMatch) {
            \Alert::warning('Invalid Item Selected')->flash();
            return redirect()->to('admin/quickbooks/products-and-services');
        }

        return self::accounts($name);
    }

    public function accounts($name)
    {
        $category_selected = str_replace_first('-', '', title_case($name));
        $accounts = ['assets' => [], 'revenues' => [], 'expenses' => []];

        if($name == 'inventory') {
            $assets   = $this->dataService->Query("SELECT * FROM Account WHERE AccountSubType = 'Inventory'");
            $revenues = $this->dataService->Query("SELECT * FROM Account WHERE AccountSubType = 'SalesOfProductIncome'");
            $expenses = $this->dataService->Query("SELECT * FROM Account WHERE AccountSubType = 'SuppliesMaterialsCogs'");
            $accounts = ['assets' => $assets, 'revenues' => $revenues, 'expenses' => $expenses];
        }

        else if($name == 'non-inventory') {
            $items       = $this->dataService->Query("SELECT * FROM Item");
            $accountRefs = collect($this->dataService->Query("SELECT * FROM Account"))->groupBy('AccountType');
            $accounts    = ['accountRefs' => $accountRefs, 'items' => $items];
        }

        else if($name == 'service') {
            $items       = $this->dataService->Query("SELECT * FROM Item");
            $accountRefs = collect($this->dataService->Query("SELECT * FROM Account"))->groupBy('AccountType');
            $accounts    = ['accountRefs' => $accountRefs, 'items' => $items];
        }

        else if($name == 'bundle') {
            $items       = $this->dataService->Query("SELECT * FROM Item");
            $accounts    = ['items' => $items];
        } 
        
        else {
            \Alert::warning("Invalid Category Type")->flash();
            return redirect()->back();
        }

        $accounts = (object)$accounts;
        return view('quickbooks.productsAndServices.category.' . $category_selected, compact('category_selected', 'accounts'));
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
