@php
require_once "../vendor/koolphp/koolreport/autoload.php";
use koolreport\widgets\google\ColumnChart;
use koolreport\datagrid\DataTables;
use \koolreport\widgets\koolphp\Table;

    $data = array(
        array("category"=>"Books","sale"=>32000,"cost"=>20000,"profit"=>12000),
        array("category"=>"Accessories","sale"=>43000,"cost"=>36000,"profit"=>7000),
        array("category"=>"Phones","sale"=>54000,"cost"=>39000,"profit"=>15000),
        array("category"=>"Movies","sale"=>23000,"cost"=>18000,"profit"=>5000),
        array("category"=>"Others","sale"=>12000,"cost"=>6000,"profit"=>6000)
    );
@endphp
@extends('backpack::layout')

@section('header')
    <section class="content-header">
      
    </section>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
      
        @php
            $students = new \App\Models\Student;

            // dd($students->limit(1)->get()->toArray());
        @endphp

        @php
            Table::create(array(
                "language"=>"de",
                "dataSource"=> $students->limit(1)->select(['id' , 'studentnumber', 'schoolyear'])->get()->toArray(),
                "options"=>array(
                    "paging"=>true,
                    'colReorder'=>true,
                ),
                'cssClass'=>array(
                    'table' => 'box table table-striped table-hover display responsive nowrap m-t-0 dataTable dtr-inline',
                    'th' => 'reportHeader',
                    'tr' => 'reportRow',
                    
                    'tf' => 'reportFooter'
                ),
                'cssId' => array (
                    'table' => 'crudTable'
                )
            ));
        @endphp

    </div>
</div>

@endsection
