<?php

namespace App\Http\Controllers\Quickbooks;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use QuickBooksOnline\API\Data\IPPEmployee;
use QuickBooksOnline\API\Facades\Employee;

class EmployeeController extends Quickbooks
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = $this->dataService->Query('SELECT * FROM Employee');
        // $items = $this->dataService->Query('SELECT * FROM Item');
        $employees = $employees == null ? [] : $employees;
        $error = $this->dataService->getLastError();

        // return response()->json($employees[0]);

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
        return view('quickbooks.employee.list', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = "CREATE";
        return view('quickbooks.employee.create', compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $theResourceObj = Employee::create([
            'EmployeeNumber'        => $request->EmployeeId,
            'SSN'                   => $request->EmployeeIdNo,

            'PrimaryAddr'           =>  [
                                            'Line1'                  => $request->Address,
                                            'City'                   => $request->CityTown,
                                            'Country'                => $request->Country,
                                            'CountrySubDivisionCode' => $request->StateProvince,
                                            'PostalCode'             => $request->ZIPCode
                                        ],

            'Title'                 => $request->Title,
            'GivenName'             => $request->FirstName,
            'MiddleName'            => $request->MiddleName,
            'FamilyName'            => $request->LastName,
            'Suffix'                => $request->Suffix,

            'DisplayName'           => $request->DisplayName,
            'PrintOnCheckName'      => $request->PrintOnCheckName,
            'BillRate'              => $request->BillingRate,
            'BillableTime'          => $request->Billable == "on" ? true : false,

            'PrimaryEmailAddr'      => [ 'Address'        => $request->Email  ],
            'PrimaryPhone'          => [ 'FreeFormNumber' => $request->Phone  ],
            'Mobile'                => [ 'FreeFormNumber' => $request->Mobile ],
            'BirthDate'             => $request->DateOfBirth,
            'HiredDate'             => $request->HireDate,
            'ReleasedDate'          => $request->Released,
        ]);

        $resultingEmployeeObj = $this->dataService->Add($theResourceObj);
        $error                = $this->dataService->getLastError();

        if ($error) {

           \Alert::warning($error->getResponseBody())->flash();
            return redirect()->back()->withInput($request->input());

        }
        else {    

            \Alert::success('Successfully Added')->flash();
            return redirect()->to('admin/quickbooks/employee');

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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {  
        $action   = "EDIT";
        $employee = $this->dataService->FindById('Employee', $id);
        $error    = $this->dataService->getLastError();
        // dd($employee);
        if ($error) {

           \Alert::warning($error->getResponseBody())->flash();
            return redirect()->back()->withInput($request->input());

        }

        $request->request->set("Title",             $employee->Title ?? '');
        $request->request->set("FirstName",         $employee->GivenName ?? '');
        $request->request->set("MiddleName",        $employee->MiddleName ?? '');
        $request->request->set("LastName",          $employee->FamilyName ?? '');
        $request->request->set("Suffix",            $employee->Suffix ?? '');
        $request->request->set("DisplayName",       $employee->DisplayName ?? '');
        $request->request->set("PrintOnCheckName",  $employee->PrintOnCheckName ?? '');
        $request->request->set("Address",           $employee->PrimaryAddr->Line1 ?? '');
        $request->request->set("CityTown",          $employee->PrimaryAddr->City ?? '');
        $request->request->set("StateProvince",     $employee->PrimaryAddr->CountrySubDivisionCode ?? '');
        $request->request->set("Country",           $employee->PrimaryAddr->Country ?? '');
        $request->request->set("ZIPCode",           $employee->PrimaryAddr->PostalCode ?? '');
        $request->request->set("Email",             $employee->PrimaryEmailAddr->Address ?? '');
        $request->request->set("Phone",             $employee->PrimaryPhone->FreeFormNumber ?? '');
        $request->request->set("Mobile",            $employee->Mobile->FreeFormNumber ?? '');
        $request->request->set("BillingRate",       $employee->BillRate ?? '');
        $request->request->set("Billable",          $employee->BillableTime ?? '');
        $request->request->set("EmployeeIdNo",      $employee->SSN ?? '');
        $request->request->set("EmployeeId",        $employee->EmployeeNumber ?? '');
        $request->request->set("Gender",            $employee->Gender ?? '');
        $request->request->set("HireDate",          $employee->HiredDate ?? '');
        $request->request->set("Released",          $employee->ReleasedDate ?? '');
        $request->request->set("DateOfBirth",       $employee->BirthDate ?? '');
        $request->flash();

        return view('quickbooks.employee.create', compact('action', 'id'))->withInput($request->input());
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
        // dd($request);
        $entities = $this->dataService->FindById('Employee', $id);
        $error    = $this->dataService->getLastError();

        if ($error) {

            \Alert::warning($error->getResponseBody())->flash();
            return redirect()->back()->withInput($request->input());

        }

        if (empty($entities)) {
            \Alert::warning("No Record for the Customer")->flash();
            return redirect()->back()->withInput($request->input());
        }

        //Get the first element
        // dd($entities);
        // $theEmployee = reset($entities);

        $updateEmployee = Employee::update($entities, [
            'sparse'                => 'false',
            'EmployeeNumber'        => $request->EmployeeId,
            'SSN'                   => $request->EmployeeIdNo,
            'PrimaryAddr'           =>  [
                                            'Line1'                  => $request->Address,
                                            'City'                   => $request->CityTown,
                                            'Country'                => $request->Country,
                                            'CountrySubDivisionCode' => $request->StateProvince,
                                            'PostalCode'             => $request->ZIPCode
                                        ],
            'Title'                 => $request->Title,
            'GivenName'             => $request->FirstName,
            'MiddleName'            => $request->MiddleName,
            'FamilyName'            => $request->LastName,
            'Suffix'                => $request->Suffix,
            'DisplayName'           => $request->DisplayName,
            'PrintOnCheckName'      => $request->PrintOnCheckName,
            'PrimaryEmailAddr'      => [ 'Address'        => $request->Email  ],
            'PrimaryPhone'          => [ 'FreeFormNumber' => $request->Phone  ],
            'Mobile'                => [ 'FreeFormNumber' => $request->Mobile ],
            'BillRate'              => $request->BillingRate,
            'BillableTime'          => ( isset($request->Billable) && $request->Billable ) == "on" ? true : false,
            'Gender'                => $request->Gender,
            'HiredDate'             => $request->HireDate,
            'ReleasedDate'          => $request->Released,
            'BirthDate'             => $request->DateOfBirth,
        ]);

        $resultingEmployeeUpdatedObj = $this->dataService->Update($updateEmployee);
        $error                       = $this->dataService->getLastError();

        if ($error) {

           \Alert::warning($error->getResponseBody())->flash();
            return redirect()->back()->withInput($request->input());

        }
        else {    

            \Alert::success('Successfully Updated')->flash();
            return redirect()->to('admin/quickbooks/employee');

        }
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
