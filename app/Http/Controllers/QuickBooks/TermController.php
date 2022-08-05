<?php

namespace App\Http\Controllers\QuickBooks;

use Illuminate\Http\Request;

use QuickBooksOnline\API\Data\IPPTerm;
use QuickBooksOnline\API\Facades\Term;

class TermController extends QuickBooks
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $terms = $this->dataService->Query("SELECT * FROM Term");
        return view('quickbooks.term.list', compact('terms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = "CREATE";
        return view('quickbooks.term.create', compact('action'));
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
        //
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
        $term = $this->dataService->FindById('Term', $id);
        $error    = $this->dataService->getLastError();

        if ($error) {
           \Alert::warning($error->getResponseBody())->flash();
            return redirect()->back()->withInput($request->input());
        }

        $request->request->set("Name", $term->Name ?? '');
        $request->request->set("DueDays", $term->DueDays ?? '');
        $request->flash();

        return view('quickbooks.term.create', compact('action', 'id'))->withInput($request->input());
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
        $entities = $this->dataService->FindById('Term', $id);
        $error    = $this->dataService->getLastError();

        if ($error) {

            \Alert::warning($error->getResponseBody())->flash();
            return redirect()->back()->withInput($request->input());

        }

        if (empty($entities)) {
            \Alert::warning("No Record for the Term")->flash();
            return redirect()->back()->withInput($request->input());
        }

        $updateTerm = Term::update($entities, [
            'Name' => $request->Name,
            'DueDays' => $request->DueDays
        ]);

        $resultingTermUpdatedObj = $this->dataService->Update($updateTerm);
        $error = $this->dataService->getLastError();

        if ($error) {
           \Alert::warning($error->getResponseBody())->flash();
            return redirect()->back()->withInput($request->input());
        }
        else {    
            \Alert::success('Successfully Updated')->flash();
            return redirect()->to('admin/quickbooks/term');
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
        abort(401);
    }
}
