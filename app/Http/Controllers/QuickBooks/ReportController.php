<?php

namespace App\Http\Controllers\QuickBooks;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Purchase;
use QuickBooksOnline\API\Data\IPPPurchase;
use QuickBooksOnline\API\QueryFilter\QueryMessage;
use QuickBooksOnline\API\ReportService\ReportService;
use QuickBooksOnline\API\ReportService\ReportName;
use Illuminate\Support\Str;

class ReportController extends QuickBooks
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $data = "false";
        return view('quickbooks.report.list', compact('data'));
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

    public function rows($arr,$test){  
        foreach($arr as $keyl2=> $v){
            $marker = 0;
            foreach($v as $k=>$v2){ 
                
                foreach($v2 as $k2 => $v3){
                                        
                    if(strcmp($k2,"Header")==0){                        
                        $colmnctr=0;            
                        foreach($v3["ColData"] as $key3=>$v4){
                            if($v4['value'] == null){
                                $arraytemp[$marker][$GLOBALS['columns'][$colmnctr]['value']] =  "";
                            }
                            else{                               
                                $arraytemp[$marker][$GLOBALS['columns'][$colmnctr]['value']] =  $v4['value'];

                            }                           
                            $colmnctr++;
                        }
                        $arraytemp[$marker]['expanded'] =  "true";
                                                
                    }
                    elseif(strcmp($k2,"Rows")==0){  
                        $arraytemp[$marker]['children'] = $this->rows($v3,1);
                        $marker++;                                          
                    }
                    elseif(strcmp($k2,"Summary")==0){                       
                        $colmnctr=0;
                        $marker = count($arraytemp);           
                        foreach($v3["ColData"] as $key3=>$v4){
                            if($v4['value'] == null){
                                $arraytemp[$marker][$GLOBALS['columns'][$colmnctr]['value']] =  "";
                            }
                            else{                               
                                $arraytemp[$marker][$GLOBALS['columns'][$colmnctr]['value']] =  $v4['value'];
                            }                           
                            $colmnctr++;
                        }
                        $marker++;
                    }
                    elseif(strcmp($k2,"ColData")==0){
                        $colmnctr=0;
                        
                        foreach($v3 as $key3=>$v4){
                            if($v4['value'] == null){
                                $arraytemp[$marker][$GLOBALS['columns'][$colmnctr]['value']] =  "";
                            }
                            else{                               
                                $arraytemp[$marker][$GLOBALS['columns'][$colmnctr]['value']] =  $v4['value'];
                            }                           
                            $colmnctr++;
                        }
                        $marker++;                                              
                    }

                }               
                
            }
            if(is_array($arraytemp)){
                    return $arraytemp;
            }                       
        }
        
    }

    public function columnsvalue($arr){ 
        $ctr = 0;
        foreach($arr['Column'] as $keyl2=>$v){
            if($v['ColTitle']==null){
                $GLOBALS['columns'][$ctr]['value'] = "";
            }
            else{
                $GLOBALS['columns'][$ctr]['value'] = $v['ColTitle'];
            }
            
            if(array_key_exists("ColType",$v)){
                $GLOBALS['columns'][$ctr]['type'] = $v['ColType']; 
            }
            else{
                $GLOBALS['columns'][$ctr]['type'] = ""; 
            } 
            
            $ctr++;           
        } 
        
                                    
    }
    public function header($arr){ 

        
        return $title;
                                  
    }

    public function select(){
                $title = "Select Report";
                $data = "false";
                $datafield = "false";
                $column = "false";
                $x = 0;                
            return view('reports.accounting.qboreports', compact('data','datafield','column','title' , 'x'));
    }

    public function reports(Request $request){     
        $x = $request["index"];
        $reportname = $request["rtype"];
        $from = $request["datefromtext"];
        $to =  $request["datetotext"];
        $displaycolumns = $request["displaycolumns"];
        $accountingMethod = $request["accountingMethod"];
        $serviceContext = $this->dataService->getServiceContext();
        $reportService = new ReportService($serviceContext);
            if (!$reportService) {
                exit("Problem while initializing ReportService.\n");
            }
        if($x == 0){              
                                
        }
        else if($x == 5 || $x == 12 || $x == 13 || $x == 22){
            $reportService->setStartDate($from);
            $reportService->setEndDate($to);
            $reportService->setAccountingMethod($accountingMethod); 
            $reportService->setSummarizeColumnBy($displaycolumns);                        
        }
        else if( $x < 5 || $x == 7 || $x == 8 || $x == 20 || $x == 21){
            $reportService->setStartDuedate($from);
            $reportService->setEndDuedate($to);
            if(strcmp($accountingMethod,"Cash")){
                $reportService->setAgingMethod("Current");
            }
            else{
                $reportService->setAgingMethod("Report Date");
            }
            
        }
        else if( $x == 9 || ($x > 13 && $x < 20)){
            $reportService->setStartDate($from);
            $reportService->setEndDate($to);
            if($x != 18){
                $reportService->setAccountingMethod($accountingMethod); 
            }
            
        }
        else if($x == 6){
            $reportService->setStartDate($from);
            $reportService->setEndDate($to);
            $reportService->setSummarizeColumnBy($displaycolumns);  
        }
        else if($x == 11){
            $reportService->setStartDuedate($from);
            $reportService->setEndDuedate($to);
        }      
        $reportdata = $reportService->executeReport(Str::studly($reportname));      
        
        
        
        // Prep Data Services
        
        
        

                   
        //$reportService->setAccountingMethod("Accrual");
       // $reportService->setSummarizeColumnBy("Days");
        
            
            $d = json_encode($reportdata);
            $d = json_decode($d,true);
           

           
            $GLOBALS['columns'] = array(); //data for columns
            $title = (trim($Words = preg_replace('/(?<!\ )[A-Z]/', ' $0', $d['Header']['ReportName'])));
                      
            if($d==null){

            }
            else{
                foreach ($d as $key => $v) {  
                    if(strcmp($key,"Columns")==0){
                        $this->columnsvalue($v);                  
                    }                   
                }            
                
                foreach ($d as $key => $v) {                        
                    if(strcmp($key,"Rows")==0){
                        $array_table = $this->rows($v,0);                  
                    }                  
                }
                
                //setdatafields for viewer
                $datafield = "[";
                $column = "[";
                if(count($GLOBALS['columns'])<10){
                   $w = 100/count($GLOBALS['columns']); 
                }
                else{
                    $w = 10;
                }
                
                $ctr = 0;
                foreach($GLOBALS['columns'] as $key => $c){
                    $v = $c['value'];
                    $c2 = strtoupper($c['value']);
                    $datafield=$datafield . "{ name:'$v',type:'string'},";
                   
                    if(strcmp($c['type'],"Money")==0){
                        $column = $column . "{ text: '$c2', dataField: '$v', cellsFormat: 'c2', align: 'center', cellsAlign: 'right', cellClassName: cellClass, width: '$w%'},";
                    }elseif($ctr==0){
                        $column = $column . "{ text: '$c2', dataField: '$v', align: 'center', cellClassName: cellClass, width: '$w%'},";
                    }
                    else{
                         $column = $column . "{ text: '$c2', dataField: '$v', align: 'center', cellsAlign: 'center', cellClassName: cellClass, width: '$w%'},";
                    }
                    $ctr++;
                }
                $datafield=$datafield . "{ name:'expanded',type:'bool'},{name: 'children' ,type: 'array'}]";
                $column = $column . "]";

                //{ text: 'Total', dataField: 'total', cellsFormat: 'c2', align: 'center', cellsAlign: 'right', width: 150 }
                $data = json_encode($array_table);
            
            // $report = json_encode($balance_sheet);     


            return view ('reports.accounting.qboreports', compact('data','datafield','column','title' , 'x'));
            }
            
    }

}
