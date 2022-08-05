<!-- <div class="col-md-6">
    <button type="button" class="btn btn-primary btn-lg w-100 h-100 active" style="width: 100%;">Student</button>
</div>
<div class="col-md-6">
    <button type="button" class="btn btn-primary btn-lg w-100 h-100" style="width: 100%;">Employee</button>
</div> -->


<create-quiz></create-quiz>

{{-- FIELD EXTRA CSS  --}}
{{-- push things in the after_styles section --}}




    {{-- FIELD EXTRA JS --}}
    {{-- push things in the after_scripts section --}}

@push('crud_fields_scripts')
    <!-- no scripts -->
    {{--         <script src="{{ asset('js/easy-autocomplete.min.js') }}"></script>
    <script src="{{ asset('js/accounting.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
    --}}
    <script>
        
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script> 
    {{-- <script src="{{ asset('js/student-accounting.js') }}"></script> --}}
    <script src='{{ \Request::getSchemeAndHttpHost() }}/js/app.js' charset="utf-8"></script>
@endpush