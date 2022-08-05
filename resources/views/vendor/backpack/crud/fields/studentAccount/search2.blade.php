<!-- text input -->
 <student-account></student-account>
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-addon">{!! $field['prefix'] !!}</div> @endif
        <div class="container-fluid" style="padding: 0; margin: 0;">
            
        <div class="col-md-11 col-sm-10" style="padding: 0; margin: 0">
            <input
                type="text"
                name="{{ $field['name'] }}"
                value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
                @include('crud::inc.field_attributes')
            >
        </div>
        <div class="col-md-1 col-sm-2"  style="padding: 0; margin: 0">
            <a href="#" class="btn btn-primary btn-block" style="border-radius: 0;">Search</a>
        </div>
        </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

{{-- <student-account></student-account> --}}




<!-- Modal -->
<div id="addPaymentModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Payment</h4>
      </div>
      <div class="modal-body">
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" min="1" class="form-control" id="payment_amount">
            </div>
            <div class="form-group">
                <label for="payment_method">Payment Method</label>
                <select name="payment_method" id="payment_method" class="form-control">
                    <option value="" selected disabled>Choose Payment Method</option>
                    @php 
                        $payment_methods = \App\Models\CashAccount::select('id', 'name')->get();
                    @endphp
                    @foreach($payment_methods as $pm) 
                        <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                    @endforeach
                </select>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="savePayment()">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="addOtherProgramModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Program</h4>
      </div>
      <div class="modal-body">
                <div class="form-group">
                    @php 
                        $programs = \App\Models\OtherProgram::all();
                    @endphp

                    <label for="">Program</label>
                    <select name="" id="program_description" class="form-control">
                            <option value="" selected disabled>Select a program</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->id }}" program-amount="{{ $program->amount }}">{{ $program->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Amount</label>
                    <input type="text" class="form-control" id="program_amount" readonly>
                </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="saveOtherProgram()" id="addProgram">Add</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

{{-- FIELD EXTRA CSS  --}}
{{-- push things in the after_styles section --}}

    @push('crud_fields_styles')
        <!-- no styles -->
{{--         <link rel="stylesheet" href="{{ asset('css/easy-autocomplete.min.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css"> --}}

        <style>
        </style>
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
        <script src='http://127.0.0.1:8000/js/app.js' charset="utf-8"></script>
    @endpush