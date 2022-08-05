<!-- text input -->


<student-account></student-account>


{{-- FIELD EXTRA CSS  --}}
{{-- push things in the after_styles section --}}

@push('crud_fields_styles')

@endpush


{{-- FIELD EXTRA JS --}}
{{-- push things in the after_scripts section --}}

@push('crud_fields_scripts')
    <!-- no scripts -->
{{--         <script src="{{ asset('js/easy-autocomplete.min.js') }}"></script>
    <script src="{{ asset('js/accounting.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
    --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script> 
    {{-- <script src="{{ asset('js/student-accounting.js') }}"></script> --}}
    <script>
        
        $('.box-footer').remove();
    
    </script>
    <script src='{{ \Request::getSchemeAndHttpHost() }}/js/app.js' charset="utf-8"></script>
@endpush