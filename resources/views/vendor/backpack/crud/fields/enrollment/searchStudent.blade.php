<!-- text input -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-addon">{!! $field['prefix'] !!}</div> @endif
                    <label>Search Record:</label>
                    <div class="container-fluid row">

                        <div class="col-md-10 col-lg-10" style="padding: 0; margin: 0;">
                            <input
                                type="text"
                                name="{{ $field['name'] }}"
                                value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
                                @include('crud::inc.field_attributes')
                            >
                        </div>
                        <div class="col-md-2 col-lg-2" style="padding: 0; margin: 0;">
                            <a href="#" id="searchStudent" class="btn btn-primary btn-block w-100" style="border-radius: 0; height: 34px;">Search</a>
                        </div>
                    </div>
                    
                    <div style="padding-top: 40px; padding-bottom: 40px;">
                        <label class="pt-5">Enrollment for:</label>
                        <table class="table table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <td><b><small>Student ID:</small></b></td>
                                    <td id="studentID">&nbsp;</td>
                                    
                                    <td><b><small>Fullname:</small></b></td>
                                    <td id="fullname">&nbsp;</td>

                                    <td><b><small>Year:</small></b></td>
                                    <td id="year">&nbsp;</td>
                                </tr>
                                <tr> 
                                    <td><b><small>Department:</small></b></td>
                                    <td id="department">&nbsp;</td>

                                    <td><b><small>Level:</small></b></td>
                                    <td id="gradeLevel">&nbsp;</td>
                                    
                                     <td><b><small>Track:</small></b></td>
                                    <td id="track">&nbsp;</td>

                                  {{--   <td><b><small>Status:</small></b></td>
                                    <td id="enroll_status">&nbsp;</td> --}}
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <label class="pt-5">Enrolled to:</label>

                   
    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>


<!-- Modal -->
<div id="studentsModal" class="modal fade" role="dialog">
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

        {{-- PREVENT FORM ON SUBMIT --}}
        $("form").submit(function(e){
            e.preventDefault();
        });
        
        $("form").bind("keypress", function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
            }
        });

        $('button[type="submit"]').click(function () {
            $("form")[0].submit();
        });

        function getStudent (id, cb)
        {
            $.ajax({
                url: '/admin/api/get/student/' + id,
                success: function (response) {
                    cb(response);
                }
            });

        }

        function selectStudent(id) {
            getStudent(id, function (student) {
                window.student    = student;
                var studentNumber = student.studentnumber;
                var fullname      = student.fullname;
                var gradeLevel    = student.current_level;
                var schoolYear    = student.school_year_name;
                var is_enrolled   = student.is_enrolled;

                var department    = student.department_name;
                var track         = student.track_name;

                if(is_enrolled == "Enrolled") {
                     $('#enroll_status').html('<span class="badge" style="background: #0d9e0d;"><b>' + is_enrolled + '</b></span>');
                } else {
                     $('#enroll_status').html('<span class="badge" style="background: pink;"><b>' + is_enrolled + '</b></span>');
                }

                $('#studentID').text(studentNumber);
                $('#fullname').text(fullname);
                $('#gradeLevel').text(gradeLevel);
                $('#year').text(schoolYear);
                $('#department').text(department);
                $('#track').text(track);

                $('#studentNumber').val(student.studentnumber);
                $('#student_id').val(student.id);

                $('#studentsModal').modal('hide');
                $('#searchInput').val('');
            });
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
                        var students = "";
                        $.each(response.data, function (key, val) {
                            students += "<tr id='student-" + val.id + "'>\
                                            <td id='student-number'     style='vertical-align:middle'>" + val.studentnumber + "</td>\
                                            <td id='student-fullname'   style='vertical-align:middle'>" + val.firstname + ' ' + val.middlename + ' ' + val.lastname + "</td>\
                                            <td id='student-level'      style='vertical-align:middle'>" + val.current_level + "</td>\
                                            <td id='student-year'       style='vertical-align:middle'>" + val.school_year_name + "</td>\
                                            <td>\
                                                <a href='#' onclick='selectStudent(" + val.id + ")' class='btn btn-primary btn-block'>Select</a>\
                                            </td>\
                                        </tr>";
                        });

                        $('#studentsModal .modal-body table > tbody').html(students);

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

                        $('#studentsModal .modal-body nav > ul').html(prev + next);

                        enablePaginateButton()
                    }
                });
        }

        jQuery(document).ready(function ($) {

            function loadStudent() {
                var studentNumber = $('#studentNumber').val();
                $.ajax({
                    url: window.location.protocol + '//' + location.host + '/admin/api/get/student/' + studentNumber,
                    success: function (response) {
                        if(jQuery.isEmptyObject(response)) {
                            return;
                        }
                        selectStudent(response);
                    }
                });
            }


            function addslashes( str ) {
                return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
            }

            function searchStudent () {
                var searchInput = $('#searchInput').val();

                if(searchInput.length == 0)  { return false; }

                $('#studentsModal').modal('toggle');
                $('#searchString').text('"' + searchInput + '"');

                $.ajax({
                    url: window.location.protocol + '//' + location.host + '/admin/api/student/search/' + searchInput,
                    success: function (response) {
                        var students = "";
                        $.each(response.data, function (key, val) {
                            var json = JSON.stringify(val, undefined, '\t');
                            students += "<tr id='student-" + val.id + "'>\
                                            <td id='student-number'     style='vertical-align:middle'>" + val.studentnumber + "</td>\
                                            <td id='student-fullname'   style='vertical-align:middle'>" + val.firstname + ' ' + val.middlename + ' ' + val.lastname + "</td>\
                                            <td id='student-level'      style='vertical-align:middle'>" + val.current_level + "</td>\
                                            <td id='student-year'       style='vertical-align:middle'>" + val.school_year_name + "</td>\
                                            <td>\
                                                <a href='#' onclick='selectStudent(" + val.id + ")' class='btn btn-primary btn-block'>Select</a>\
                                            </td>\
                                        </tr>";
                        });

                        var studentsTable = "<table class='table table-striped table-bordered'>\
                                                <thead>\
                                                    <th>StudentNumber</th>\
                                                    <th>Fullname</th>\
                                                    <th>Grade Level</th>\
                                                    <th>Year</th>\
                                                    <th>SELECT</th>\
                                                </thead>\
                                                <tbody>\
                                                    " + students + "\
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

                        $('#studentsModal .modal-body').html(studentsTable + paginationNav);
                    }
                });
            }

            if($('#studentNumber').val().length > 0) {
                loadStudent();
            }


            $('#studentsModal').on('hidden.bs.modal', function (e) {
                var imageURL = "{{ asset('images/magnify-glass-200px.gif') }}";
                $(this).find('.modal-body')
                        .html('<center>\
                                    <img class="img-responsive" src="'+imageURL+'" alt="Magnifying Glass">\
                                    <h3>Searching for <span id="searchString"></span></h3>\
                                </center>');
            })


            $('#searchStudent').click(function () {
                searchStudent();
            });

            $('#searchStudent').keypress(function (e) {
                if(e.which == 13) {
                    searchStudent();
                }
            });

            $('#searchInput').keypress(function (e) {
                if(e.which == 13 || e.which === 'Enter') {
                    searchStudent();
                }
            });
        });
    </script>
    @if($crud->getActionMethod() === "edit")
        @php
            $student = null;
            if($entry->student_id !== null) {
                $student = App\Models\Student::where('id', $entry->student_id)->first();
            }
            else {
                $student = App\Models\Student::where('studentnumber', $entry->studentnumber)->first();
            }
        @endphp
        <script>
            $('#studentID').text('{{ $entry->studentnumber }}');
            $('#fullname').text('{{ $student ? $student->fullname : '-' }}');
            $('#gradeLevel').text('{{ $student ? $student->current_level : '-' }}');
            $('#year').text('{{ $student ? $student->school_year_name : '-' }}');

            $('#studentNumber').val({{ $entry->studentnumber }});
        </script>
    @endif
@endpush