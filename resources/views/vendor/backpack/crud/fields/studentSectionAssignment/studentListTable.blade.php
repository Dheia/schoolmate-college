<div @include('crud::inc.field_wrapper_attributes') style="margin-top: 50px;" >
    
        {{-- HINT --}}
    
    <div class="col-md-12" style="padding: 0; padding-bottom:  30px;">
        @if ($errors->has('students'))
            <p class="help-block" style="color: #dd4b39;">{!! $errors->first('students') !!}</p>
        @endif
        <input type="text" class="form-control" id="searchStudent" placeholder="Search And Add Student Here...">
    </div>

    {{-- <label>{!! $field['label'] !!} </label> --}}
    @include('crud::inc.field_translatable_icon')

    <table class="table table-striped table-bordered">
        <thead>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Gender</th>
            <th>Action</th>
        </thead>
        <tbody></tbody>
    </table>
</div>
{{-- {{ dd(json_decode(old('students'))) }} --}}
{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
    {{-- @push('crud_fields_styles')
        {{- YOUR CSS HERE --}}
        <link rel="stylesheet" href="{{ asset('css/easy-autocomplete.min.css') }}">
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')

        <script type="text/javascript" src="{{ asset('js/easy-autocomplete.min.js') }}"></script>
        {{-- <script src="https://cdn.jsdelivr.net/npm/lodash@3.10.1/index.min.js"></script> --}}
        <script>

            var students = [];
            @if(old('students') !== null)
                students = JSON.parse({!! json_encode(old('students')) !!});

                @php
                    $old_val_students = App\Student::where('studentnumber', json_decode(old('students')))->get(['id', 'firstname', 'middlename', 'lastname', 'studentnumber', 'gender']);
                @endphp


                var old_val_students = {!! json_encode($old_val_students) !!}

                $.each(old_val_students, function (key, val) {
                    var studentRow =    "<tr id='tr-" + val.studentnumber + "'>\
                                            <td>" + val.studentnumber + "</td>\
                                            <td>" + val.firstname + ' ' + val.middlename + ' ' + val.lastname + "</td>\
                                            <td>" + val.gender + "</td>\
                                            <td><a href='javascript:void(0)' onclick='removeItemToTable(" + val.studentnumber + ")' class='btn btn-danger'>Remove</a></td>\
                                        </tr>";

                    $('table tbody').append(studentRow);
                });

            @endif

            function updateItems () {
                $('#students').val(JSON.stringify(students));
            }

            function isStudentExist(studentnumber) {
                return students.indexOf(studentnumber) !== -1 ? true : false;
            }

            function addItemToTable (index, data) {
                console.log(data);

                if(isStudentExist(data.studentnumber)) { alert("Student Has Been Already Added!"); return; }

                var studentRow =    "<tr id='tr-" + data.studentnumber + "'>\
                                        <td>" + data.studentnumber + "</td>\
                                        <td>" + data.fullname + "</td>\
                                        <td>" + data.gender + "</td>\
                                        <td><a href='javascript:void(0)' onclick='removeItemToTable(" + data.studentnumber + ")' class='btn btn-danger'>Remove</a></td>\
                                    </tr>";

                students.push(data.studentnumber);                      
                $('table tbody').append(studentRow);
                updateItems();
            }

            function removeItemToTable (studentnumber) {
                var index = students.indexOf(studentnumber);
                students.splice(index, 1); 
                $('#tr-' + studentnumber).remove();
                updateItems();
            }

            $(document).ready(function () {            
                var options = {
                    url: "{{ url($crud->route) }}/student/search",
                    getValue: function (element) {
                        return element.studentnumber + ' | ' + element.fullname + ' | ' + element.gender;
                    },
                    list: {
                        onChooseEvent: function() {
                            var index = $("#searchStudent").getSelectedItemIndex();
                            var data  = $("#searchStudent").getSelectedItemData();
                            addItemToTable(index, data);
                        }	
                    },
                    ajaxSettings: {
                        method: "POST",
                        data: {
                            dataType: "json",
                        }
                    },
                    preparePostData: function(data) {
                        data.phrase     = $("#searchStudent").val();
                        data.level_id   = $('select[name=level_id]').find('option:selected').val();
                        data.section_id = $('select[name=section_id]').find('option:selected').val();
                        return data;
                    },
                    requestDelay: 400,

                    // list: {   
                    //     match: {
                    //         enabled: true
                    //     }
                    // },
                    theme: "square"
                };

                $("#searchStudent").easyAutocomplete(options);

                @if($actionMethod === 'edit' || $actionMethod === 'clone')

                    $.ajax({
                        url: '{{ url($crud->route) }}/' + {{ $entry->id }} + '/student',
                        success: function (response) {
                            
                            $.each(response, function (key, val) {
                                var studentRow =    "<tr id='tr-" + val.studentnumber + "'>\
                                                        <td>" + val.studentnumber + "</td>\
                                                        <td>" + val.fullname + "</td>\
                                                        <td>" + val.gender + "</td>\
                                                        <td><a href='javascript:void(0)' onclick='removeItemToTable(" + val.studentnumber + ")' class='btn btn-danger'>Remove</a></td>\
                                                    </tr>";

                                students.push(val.studentnumber);                      
                                $('table tbody').append(studentRow);
                                updateItems();
                            });

                        }
                    })

                @endif
            });
        </script>
    @endpush

@endif
{{-- @endif --}}
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
