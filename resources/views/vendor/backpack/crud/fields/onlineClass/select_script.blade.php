@push('crud_fields_scripts')
    <script>
        $(document).ready(function () {
            getTopics();
        });
        function getTopics () {
            $.ajax({
                url: "{{ route('teacher-online-post/api/get-topics') }}", 
                data:{
                    class_id: $('select[name="online_class_id"').find(":selected").val(),
                },
                success: function(result){
                    $('select[name="class_topic_id"]').empty();
                    if(result == ''){
                        var option = '<option value">' + '-' + '</option>';
                        $('select[name="class_topic_id"]').append(option);
                    }
                    else{
                        $.each(result, function(index, value) {
                            // console.log('div' + index + ':', value);
                            $('select[name="class_topic_id"]').prop('disabled', false);
                            var option = '<option value ="' + value.id + '">' + value.title + '</option>';
                            $('select[name="class_topic_id"]').append(option);
                        });
                    }
                }
            });
            getModules();
        }
        function getModules () {
            $.ajax({
                url: "{{ route('teacher-online-post/api/get-modules') }}", 
                data:{
                    topic_id: $('select[name="class_topic_id"').find(":selected").val(),
                },
                success: function(result){
                    $('select[name="class_module_id"]').empty();
                    if(result == ''){
                        var option = '<option value">' + '-' + '</option>';
                        $('select[name="class_module_id"]').append(option);
                    }
                    else{
                        $.each(result, function(index, value) {
                            // console.log('div' + index + ':', value);
                            $('select[name="class_module_id"]').prop('disabled', false);
                            var option = '<option value ="' + value.id + '">' + value.title + '</option>';
                            $('select[name="class_module_id"]').append(option);
                        });
                    }
                }
            });
        }
        $('select[name="online_class_id"').on('change', function() {
            getTopics();
        });
         $('select[name="class_topic_id"').on('change', function() {
            getModules();
        });
    </script>
@endpush