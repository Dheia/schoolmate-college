@extends('backpack::layout')

@section('header')
@endsection


@section('content')
    <!-- HEADER -->
    <div class="row" style="padding: 15px;">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
            <section class="content-header">
                <ol class="breadcrumb">
                    <li><a href="{{ url('student/dashboard') }}">Dashboard</a></li>
                    <li><a class="text-capitalize active">Student Balances Report</a></li>
                </ol>
            </section>
            <h1 class="smo-content-title">
            <span class="text-capitalize">Student Balances Report</span>
            {{-- <small>All turnstile tap in and tap out</small> --}}
            </h1>
        </div>
    </div>
    <!-- END OF HEADER -->

    <div class="row">
        <form role="form" method="POST" action="{{ url('admin/students-balances-report/download') }}" >
            {!! csrf_field() !!}

            <div class="col-md-12">
                {{-- <form action="" method="GET" class="mb-3"> --}}
                <div class="box col-md-12 padding-10 p-t-20">
                    <div class="form-group col-md-12">
                        <div class="row">

                            <!-- SCHOOL YEAR -->
                            <div class="col-md-4">
                                <label for="">School Year</label>                    
                                <select name="school_year_id" id="school_year_id" class="form-control">
                                    @if( count($schoolYears) > 0 )
                                        @foreach ( $schoolYears as $schoolYear )
                                            <option value="{{ $schoolYear->id }}">{{ $schoolYear->schoolYear }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- DEPARTMENT -->
                            <div class="col-md-4">
                                <label for="">Department</label>                    
                                <select name="department_id" id="department_id" class="form-control">
                                    <option value="">-</option>
                                    @if( count($departments) > 0 )
                                        @foreach ( $departments as $department )
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- LEVEL -->
                            <div class="col-md-4">
                                <label for="">Level</label>                    
                                <select name="level_id" id="level_id" class="form-control">
                                    <option value="">-</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- FILTER BUTTON --> 
                            <div class="col-md-6">
                            <label for="">&nbsp;</label><br>
                            <a href="javascript:void(0)" id="btn-run" class="btn btn-primary w-100">Filter</a>
                            </div>

                            <!-- DOWNLOAD BUTTON -->
                            <div class="col-md-6">
                            <label for="">&nbsp;</label><br>
                            <button id="btn-download" type="submit" class="btn btn-block btn-success" style="display: none;">
                                Download
                            </button>
                        </div>
                 
                  
                    </div>
                </div>
            </div>
            
            {{-- </form> --}}
          
            <img id="loading-gif" class="img-responsive" src="{{asset('/vendor/backpack/crud/img/ajax-loader.gif')}}" alt="Loading..." style="display: none; margin: auto; padding-top: 20px; padding-bottom: 20px;">

            <div class="box col-md-12 padding-10 p-t-20" style="display: none;" id="list-box">
                <div class="form-group col-md-12">
                    <div class="table-responsive">
                        <table id="student-table" class="table table-striped table-bordered">
                            <thead>
                                <th>STUDENT NO.</th>
                                <th>FULLNAME</th>
                                <th>LEVEL</th>
                                <th>TOTAL TUITION</th>
                                <th>TOTAL PAYMENT</th>
                                <th>REMAINING BALANCE</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </form>
    </div>

  
@endsection


@section('after_scripts')
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#loading-gif').css('display', 'none');
            $('#attendance').DataTable();

            department_id  = $('#department_id option:selected').val();

            if(! department_id) {
                $('select[name="level_id"]').html('<option>-</option>');
                return;
            }

            getDepartmentLevels();
        });
     
    </script>

    <script>
        var selectSchoolYear = $('#school_year_id');
        var selectDepartment = $('#department_id');
        var selectLevel      = $('#level_id');

        var school_year_id;
        var department_id;
        var level_id;

        /** 
         * School Year On Change
         */
        selectSchoolYear.on('change', function(e){
            $('#btn-download').attr('disabled', true);
        });

        /** 
         * School Year On Change
         */
        selectLevel.on('change', function(e){
            $('#btn-download').attr('disabled', true);
        });

        /** 
         * Department On Change
         * Get All Levels of the Selected Department
         */
        selectDepartment.on('change', function(e){

            $('#btn-download').attr('disabled', true);

            department_id  = $('#department_id option:selected').val();

            if(! department_id) {
                $('select[name="level_id"]').html('<option>-</option>');
                return;
            }

            getDepartmentLevels();
        });

        /** 
         * Get Enrollments with Total Payments and Balances 
         */
        $('#btn-run').click(function () 
        {
            school_year_id = $('#school_year_id option:selected').val();
            department_id  = $('#department_id option:selected').val();
            level_id       = $('#level_id option:selected').val();
            var empty      = '';

            if(school_year_id == "" || school_year_id == "") {
                new PNotify({
                    // title: "Error",
                    text: 'Please Select School Year.',
                    type: 'warning',
                    icon: false
                });

                // alert("Please Select School Year");
                $('#list-box').css('display', 'none');
                $('#btn-download').css('display', 'none');
                return;
            }

            if(department_id == "" || department_id == "") {
                new PNotify({
                    // title: "Error",
                    text: 'Please Select Department.',
                    type: 'warning',
                    icon: false
                });

                // alert("Please Select Department");
                $('#list-box').css('display', 'none');
                $('#btn-download').css('display', 'none');
                return;
            }

            if(level_id == "" || level_id == "") {
                new PNotify({
                    // title: "Error",
                    text: 'Please Select Level.',
                    type: 'warning',
                    icon: false
                });

                // alert("Please Select Level");
                $('#list-box').css('display', 'none');
                $('#btn-download').css('display', 'none');
                return;
            }

            busy();

            $.ajax({
                url: 'students-balances-report/api/enrollment-list',
                data: {
                    school_year_id: school_year_id,
                    department_id: department_id,
                    level_id: level_id,
                },
                success: function (response) {
                    console.log(response.enrollments.length);
                    var tableRow = '';
                    var totalAmountRow = '';
                    var total = 0;
                    var no_data = '<tr><td colspan="11" class="text-center">No Student Found. '+empty+'</td></tr>';

                    if(response.enrollments.length > 0) {
                        enrollments = response.enrollments;
                        $.each(enrollments, function (key, enrollment) {
                            console.log(enrollment.remaining_balance);
                            tableRow += '<tr>\
                                    <td>{{ Config::get('settings.schoolabbr') }}-' + enrollment.studentnumber  + '</td>\
                                    <td>' + enrollment.full_name  + '</td>\
                                    <td>' + enrollment.level_name  + '</td>\
                                    <td>' + parseFloat(enrollment.total_tuition).toLocaleString('en-US', {style: 'currency', currency: 'PHP'})+'</td>\
                                    <td>' + parseFloat(enrollment.total_payment_histories).toLocaleString('en-US', {style: 'currency', currency: 'PHP'})+'</td>\
                                    <td>' + parseFloat(enrollment.remaining_balance).toLocaleString('en-US', {style: 'currency', currency: 'PHP'})+'</td>\
                                </tr>'
                            // totalAmountRow = '<tr>\
                            //         <td colspan="10" class="text-right" ><span style="font-weight:bold">Total Amount</span></td>\
                            //         <td colspan="11" ><span style="font-weight:bold">'+ total.toLocaleString('en-US', { style: 'currency', currency: 'PHP' }) +'</span></td>\
                            //     </tr>'
                        });
                    }

                    // $('#student-table tbody').html(tableRow ? tableRow : no_data).append(totalAmountRow);
                    $('#student-table tbody').html(tableRow ? tableRow : no_data);
                    done();
                },
                error: function (error) {
                    done();
                    pNotifyError(error.responseText);
                }
                    
            });
            
        });

        /** 
         * Get Department Levels (AJAX API)
         */
        function getDepartmentLevels()
        {
            var options  = '<option selected disabled value="">Please Select Level</option>';

            $.ajax({
                url: 'students-balances-report/api/' + department_id + '/levels' ,
                success: function (response) {
                    console.log(response);
                    console.log(response.length);
                    if(response.length > 0) {
                        $.each(response, function (key, val) {
                            options += '<option value="' + val.id + '">' + val.year.toLowerCase().toUpperCase() + '</option>';
                        })

                        $('select[name="level_id"]').html(options);
                    }
                },
                error: function (error) {
                    console.log(error.responseText);
                    $('select[name="level_id"]').html('<option>-</option>');

                    new PNotify({
                        // title: "Error",
                        text: JSON.parse(error.responseText) ?? 'Something Went Wrong, Please Try Again.',
                        type: 'warning',
                        icon: false
                    });
                }
            });
        }

        /** 
         * Show Loading and Hide Student Table
         */
        function startLoading()
        {
            $('#list-box').css("display", "none");
            $('#loading-gif').css("display", "block");
        }

        /** 
         * Hide Loading and Show Student Table
         */
        function stopLoading()
        {
            $('#loading-gif').css("display", "none");
            $('#list-box').css("display", "none");
        }

        /** 
         * Busy / Loading
         */
        function busy()
        {
            $('#btn-run').text('...').attr('disabled', true);
            $('#btn-download').text('...').attr('disabled', true);
            $('#loading-gif').css('display', 'block');
            $('#list-box').css('display', 'none');
        }

        /** 
         * Done Loading
         */
        function done()
        {
            $('#loading-gif').css('display', 'none');
            $('#list-box').css('display', 'block');
            $('#btn-run').text('Run').removeAttr('disabled');
            $('#btn-download').text('Download').removeAttr('disabled');
            $('#btn-download').css('display', 'block');
        }

        /** 
         * PNotify Error
         */
        function pNotifyError(error = null)
        {
            $('#student-table tbody').html('<tr><td colspan="6" class="text-center">Something Went Wrong, Please Try To Reload The Page.</td></tr>');
            new PNotify({
                // title: "Error",
                text: error ? error : 'Something Went Wrong, Please Try Again.',
                type: 'error',
                icon: false
            });
        }
    
    </script>
@endsection

@section('after_styles')

  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@endsection
