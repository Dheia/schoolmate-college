<!-- text input -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-addon">{!! $field['prefix'] !!}</div> @endif
       {{--  <input
            type="text"
            name="{{ $field['name'] }}"
            value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
            @include('crud::inc.field_attributes')
        > --}}
        
        {{-- <form class="form-inline"> --}}
        {{--     <div class="form-group">
                <label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>
                <div class="input-group"> --}}
                    <div class="col-md-12 col-xs-12" style="padding: 0;">
                        <table class="table table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <td><b><small>Employee No:</small></b></td>
                                    <td id="employeeNo">&nbsp;</td>
                                    
                                    <td><b><small>Fullname:</small></b></td>
                                    <td id="fullname">&nbsp;</td>
                                    
                                    <td><b><small>Position :</small></b></td>
                                    <td id="position">&nbsp;</td>
                                    
                                    {{-- <td><b><small>Year:</small></b></td>
                                    <td id="year">&nbsp;</td> --}}
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-10" style="padding: 0;">
                        <input
                            type="text"
                            name="{{ $field['name'] }}"
                            value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
                            @include('crud::inc.field_attributes')
                        >
                    </div>
                    <div class="col-md-2" style="padding: 0;">
                        <a href="javascript:void(0)" id="searchEmployee" class="form-control btn btn-primary w-100">Search</a>
                    </div>
                {{-- </div> --}}
            {{-- </div> --}}
        {{-- </form> --}}

       {{-- 
        --}}

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>


<!-- Modal -->
<div id="employeesModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Search &nbsp;
                    <small>
                        [ <span id="currentPage"></span> - <span id="lastPage"></span> ]
                    </small>
                </h4>
            </div>
            <div class="modal-body">
                
                <center>
                    <img class="img-responsive" src="{{ asset('images/magnify-glass-200px.gif') }}" alt="Magnifying Glass">
                    <h3>Searching for <span id="searchString"></span></h3> 
                </center>
                
            </div>
            {{-- <div class="modal-footer"> --}}
                {{-- <button type="button" class="btn btn-primary" onclick="savePayment()">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> --}}
            {{-- </div> --}}
        </div>

    </div>
</div>


@push('crud_fields_styles')
    <!-- no styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
@endpush


{{-- FIELD EXTRA JS --}}
{{-- push things in the after_scripts section --}}

@push('after_scripts')
    <!-- no scripts -->
    {{-- <script src="{{ asset('js/student-accounting.js') }}"></script> --}}

    <script type="text/javascript">
        var emp_id = '';
        function selectStudent(json) {
            // console.log("a = ", json);
            var employee = json;
            emp_id       = employee.id;

            $('#employeeNo').text(employee.employee_id);
            $('#fullname').text(employee.full_name);
            $('#position').text(employee.position);

            $('#studentNumber').val(employee.employee_id);

            $('#employeesModal').modal('hide');
            $('#searchInput').val('');
        }

        function disablePaginateButton() {
            $('#prev').attr('disabled', true);
            $('#next').attr('disabled', true);
        }

        function enablePaginateButton() {
            $('#prev').removeAttr('disabled');
            $('#next').removeAttr('disabled');
        }

        function requestPage (url) {
                disablePaginateButton();
                $.ajax({
                    url: url,
                    success: function (response) {
                        var employees = "";
                        $.each(response.data, function (key, val) {
                            employees += "<tr id='student-" + val.id + "'>\
                                            <td id='student-number'     style='vertical-align:middle'>" + val.employee_id + "</td>\
                                            <td id='student-fullname'   style='vertical-align:middle'>" + val.full_name + "</td>\
                                            <td id='student-level'      style='vertical-align:middle'>" + val.position + "</td>\
                                            <td>\
                                                <a href='#' onclick='selectStudent(" + JSON.stringify(val) + ")' class='btn btn-primary btn-block'>Select</a>\
                                            </td>\
                                        </tr>";
                        });

                        $('#employeesModal .modal-body table > tbody').html(employees);

                        $('#currentPage').text(response.current_page);
                        $('#lastPage').text(response.last_page);


                        var prev = '';
                        var next = '';

                        if(response.prev_page_url !== null) {
                            prev = '<li><a id="next" href="#" onclick="requestPage(\'' + response.prev_page_url + '\')">Previous</a></li>';
                        }

                        if(response.next_page_url !== null) {
                            next = '<li><a id="next" href="#" onclick="requestPage(\'' + response.next_page_url + '\')">Next</a></li>';
                        }

                        $('#employeesModal .modal-body nav > ul').html(prev + next);

                        enablePaginateButton()
                    }
                });
        }

        jQuery(document).ready(function ($) {

            function loadEmployee() {
                var employeeNumber = $('#studentNumber').val();

                $.ajax({
                    url: window.location.protocol + '//' + location.host + '/admin/api/get/employee/' + employeeNumber,
                    success: function (response) {
                        if(jQuery.isEmptyObject(response)) {
                            return;
                        }
                        selectStudent(response);
                    }
                });
            }




            function searchStudent () {
                var searchInput = $('#searchInput').val();

                if(searchInput.length == 0)  {
                    new PNotify({
                        title: 'Invalid Input',
                        text: 'Please enter a Name or ID Number to search',
                        type: "warning",
                        icon: false
                    });
                    return false;
                }

                $('#employeesModal').modal('toggle');
                $('#searchString').text('"' + searchInput + '"');

                $.ajax({
                    url: window.location.protocol + '//' + location.host + '/admin/api/employee/search/' + searchInput,
                    success: function (response) {
                        var employees = "";
                        $.each(response.data, function (key, val) {
                            employees += "<tr id='employee-" + val.id + "'>\
                                            <td id='employee-number'     style='vertical-align:middle'>" + val.employee_id + "</td>\
                                            <td id='employee-fullname'   style='vertical-align:middle'>" + val.full_name + "</td>\
                                            <td id='employee-position'   style='vertical-align:middle'>" + val.position + "</td>\
                                            <td>\
                                                <a href='#' onclick='selectStudent(" + JSON.stringify(val) + ")' class='btn btn-primary btn-block'>Select</a>\
                                            </td>\
                                        </tr>";
                        });

                        var employeesTable = "<table class='table table-striped table-bordered'>\
                                                <thead>\
                                                    <th>Employee No</th>\
                                                    <th>Fullname</th>\
                                                    <th>Position</th>\
                                                    <th>SELECT</th>\
                                                </thead>\
                                                <tbody>\
                                                    " + employees + "\
                                                </tbody>\
                                            </table>";

                        $('#currentPage').text(response.current_page);
                        $('#lastPage').text(response.last_page);

                        var next = '';
                        if(response.next_page_url !== null) {
                            next = '<li><a id="next" href="#" onclick="requestPage(\'' + response.next_page_url + '\')">Next</a></li>';
                        } 
                        var paginationNav = '<nav aria-label="...">\
                                                  <ul class="pager">\
                                                    ' + next + '\
                                                  </ul>\
                                                </nav>';

                        $('#employeesModal .modal-body').html(employeesTable + paginationNav);
                    }
                });
            }

            if($('#studentNumber').val() !== '') {
                loadEmployee();
            }


            $('#employeesModal').on('hidden.bs.modal', function (e) {
                var imageURL = "{{ asset('images/magnify-glass-200px.gif') }}";
                $(this).find('.modal-body')
                        .html('<center>\
                                    <img class="img-responsive" src="'+imageURL+'" alt="Magnifying Glass">\
                                    <h3>Searching for <span id="searchString"></span></h3>\
                                </center>');
            })


            $('#searchEmployee').click(function () {
                searchStudent();
            });

            $('#searchEmployee').keypress(function (e) {
                if(e.which == 13) {
                    searchStudent();
                }
            });

            $('#searchInput').keypress(function (e) {
                if(e.which == 13) {
                    searchStudent();
                }
            });
        });
    </script>
@endpush