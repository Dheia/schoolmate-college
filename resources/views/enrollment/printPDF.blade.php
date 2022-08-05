<!DOCTYPE html> 
<html>
    <head>
        <title>{{Config::get('settings.schoolname')}} | Enrollment Form</title>
        <meta name="viewport" content="width=device-width">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>


        <style>
            @include('bootstrap4')
            
            table td, table th {
                border: 0 !important;
                padding: 3px !important;
                padding-top: 0px !important;
                padding-bottom: 0px !important;
            }

            table.profile tr td{font-size: 11px;}
            table.profile tr td:first-child{font-weight: 900;}
            table.profile tr td:nth-child(4){font-weight: 900;}
            table.profile tr td {width: 25%;}
            table.profile {margin-bottom: 0px;}

            .profilediv {
                border: 1px solid #ddd;
                border-radius: 5px;
                margin-bottom: 10px;
                padding: 5px;
            }

            .signature-over-printed-name p {
                font-size: 9px;
            }

            body {
                font-size: 10px;
                margin-bottom: 50px !important;
                margin-top: 130px !important;
            }
            header {
                position: fixed;
                top: 0px;
                height: 100px;
            }
            footer { 
                position: fixed;
                bottom: 0px; 
                height: 50px;
                font-size: 10px;

            }
        </style>
    </head>
    <body>
        <header>
            <center>    
                <img width="50" src="{{ Config::get('settings.schoollogo') }}" alt="IMG" align="center" style="">
                <p class="text-uppercase mb-0" style="font-size: 12px;"><b>{{ Config::get('settings.schoolname') }}</b></p>
                <p><small>{{ Config::get('settings.schooladdress') }}</small></p>
            </center>
            <center class="text-uppercase">
                <b>Certificate of Enrollment</b>
            </center>
        </header>

        <footer>
            <center>
                <img width="40" src="images/schoolmate_logo.jpg" alt="schoolmate_logo">
            </center>
            <center>
                <p class="mb-0">Copyright &copy; 2019</p>
                <p class="pt-0">Powered by: Tigernet Hosting and IT Services</p>
            </center>
        </footer>
        <!-- Content -->
        <main>
            <div class="col-12 m-0 p-0">
                <div class = "profilediv">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>School Year:</td>
                                <th>{{ $enrollment->schoolYear->schoolYear }}</th>
                                <td>Application Date:</td>
                                <th>{{ Carbon\Carbon::parse($enrollment->created_at)->format('M. d, Y') }}</th>
                            </tr>
                            <tr>
                               <!--  <td>Level:</td>
                                <th>{{ $enrollment->level->year }}</th> -->
                                <td>Term:</td>
                                <th class="text-capitalize">{{ $enrollment->term_type }} Term</th>
                            </tr>
                                <tr>
                                    @if($enrollment->track !== null)
                                        <td>Track</td>
                                        <th>{{ $enrollment->track->code }}</th>
                                    @endif
                                </tr>

                        </tbody>
                    </table>
                </div> <!-- .profilediv -->

                @include('student.print.student_general_information')
                

                <!-- SUBJECT / SECTION / SCHEDULE -->
                <div class = "profilediv">
                    <table class="table">
                        <thead style="background-color: #CCC">
                            <tr>
                                <th scope="col" class="text-uppercase">CODE</th>
                                <th scope="col" class="text-uppercase">Subject Title</th>
                                <th scope="col" class="text-uppercase">Units</th>
                                <th scope="col" class="text-uppercase">Section</th>
                                <th scope="col" class="text-uppercase">Schedule / Room</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_units = 0;
                                $total_subjects = 0;
                            @endphp
                            
                            @if($subject_mapping['subjects'] ?? '')
                            @foreach($subject_mapping['subjects'] as $subject)
                                <tr>
                                    <td>
                                        {{ \App\Models\SubjectManagement::find($subject->subject_code)->subject_code }}
                                    </td>
                                    <td>
                                        {{ \App\Models\SubjectManagement::find($subject->subject_code)->subject_title }}
                                    </td>
                                    <th>
                                        {{ number_format( (float) \App\Models\SubjectManagement::find($subject->subject_code)->no_unit, 1, '.', '') }}
                                    </th>
                                    <td>
                                        {{ $student_section->class_code ?? 'TBA' }}
                                    </td>
                                    <td>
                                        TBA
                                    </td>
                                </tr>
                                @php 
                                    $total_units += \App\Models\SubjectManagement::find($subject->subject_code)->no_unit;
                                    $total_subjects++;
                                @endphp
                            @endforeach
                            @else
                                <tr>
                                    <td>TBA</td>
                                    <td>TBA</td>
                                    <td>TBA</td>
                                    <td>TBA</td>
                                    <td>TBA</td>
                                </tr>
                            @endif
                            
                            <tr>
                                <th style="padding-top: 10px !important;">
                                    SUBJECT(S): {{ $total_subjects }}
                                </th>
                                <th class="text-right" style="padding-top: 10px !important;">Total Unit(s)</th>
                                <th style="padding-top: 10px !important;">
                                    {{ number_format( (float) $total_units, 1, '.', '') }}
                                </th>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div> 
                <!-- SUBJECT / SECTION / SCHEDULE -->

                <div class="row">
                    <div class="col-6" style="margin-left: 50%;">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="text-justify">
                                        I understand that by signing this information/application form, I hereby give the school and SchoolMATE to collect, record, organize, update, or modify, retrieve, consult, utilize, consolidate, block, erase, or destruct my personal data as part of my information for historical, statistical, research and evaluation purposes pursuant to the provisions of the Republic Act No. 10173 of the Philippines, Data Privacy Act of 2012 and its corresponding implementing Rules and Regulations. In consideration of my admission and of the privileges of students in this institution, I hereby abide by and comply with all rules and regulations laid down by the institution in which I am enrolled at.
                                    </td>
                                </tr>
                                <tr class="signature-over-printed-name mt-0" style="position: relative;">
                                    <td class="mt-4 text-capitalize">
                                        <b>{{ strtolower($student->fullname) }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Signature Over Printed Name
                                    </td>
                                </tr>
                                 <tr>
                                    <td class="mt-4 text-capitalize">
                                        <b>Date:</b>
                                    </td>
                                </tr>
                            </tbody>
                            
                        </table>
                    </div>
                    <div class="col-6" style="margin-right: 50%; height: 0px !important;">
                        <div class = "">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th colspan="4" class="text-uppercase text-center" style="background-color: #CCC">
                                            Student Accounts
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="pt-0 pb-0 mt-0 mb-0">
                                        <td colspan="2">Commitment Payment Type</td>
                                        <td colspan="2" class="text-right">{{ $enrollment->commitmentPayment->name }}</td>
                                    </tr>
                                    <tr class="pt-0 pb-0 mt-0 mb-0">
                                        <td colspan="2">Mandatory Fee Upon Enrollment</td>
                                         @foreach($tuition->tuition_fees as $tuition_fee)
                                            @if($tuition_fee->payment_type == $enrollment->commitment_payment_id)
                                                <td colspan="2" class="text-right">P{{ number_format($tuition_fee->total, 2, ".", ", ") }}</td>
                                            @endif
                                        @endforeach
                                    </tr>

                                    <tr>
                                        <td colspan="2">Miscellaneous</td>
                                        <td colspan="2" class="text-right">P{{ number_format($total_miscellaneous, 2, ".", ", ") }}</td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">Activities</td>
                                        <td colspan="2" class="text-right">P{{ number_format($total_activities_fee, 2, ".", ", ") }}</td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">Other Fees</td>
                                        <td colspan="2" class="text-right">P{{ number_format($total_other_fees, 2, ".", ", ") }}</td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">Tuition Fee (Payment Scheme)</td>
                                        <td colspan="2" class="text-right">P{{ number_format($total_payment_scheme, 2, ".", ", ") }}</td>
                                    </tr>

                                    <tr>
                                        <td class="mt-2 text-right" colspan="2">Grand Total</td>
                                        <td class="mt-2 text-right" colspan="2">P{{ number_format($grand_total, 2, ".", ", ") }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="2">Payment</td>
                                        <td class="text-right" colspan="2">P{{ number_format($total_payment, 2, ".", ", ") }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="2">Balance</td>
                                        <td class="text-right" colspan="2">P{{ number_format($grand_total - $total_payment, 2, ".", ", ") }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>

                <div class="row text-center">
                    <div class="col-12">
                        <table class="table" style="padding-top: 50px;">
                            <tbody>
                                <tr>
                                    @if($registrar)
                                        <th><u>{{ $registrar->full_name }}</u></th>
                                    @else
                                        <th>______________________</th>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Registrar</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>                
                </div>

            </div>
        </main>
        
    </body>
</html>