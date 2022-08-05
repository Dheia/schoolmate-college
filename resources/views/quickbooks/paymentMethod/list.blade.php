@extends("backpack::layout")

@section('header')
    <section class="content-header">
        <h1>
          Payment Methods
          {{-- <small>{{ trans('backpack::base.first_page_you_see') }}</small> --}}
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin')) }}">{{ config('backpack.base.project_name') }}</a></li>
          <li class="active">Payment Methods</li>
        </ol>
    </section>
@endsection

@push('after_styles')
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}

@section('content')
  <div class="row">
    <div class="col-md-12">

      <div class="box">

        <div class="box-header with-border">
            <a href="{{ url()->current() }}/create" class="btn btn-primary">Add Payment Method</a>
          </div>
        
        <div class="box-body">
          {{-- {{ dd($customers) }} --}}

          <table class="table table-bordered table-striped table-hoverable">
            <thead>
              <th>Name</th>
              <th>Credit Card</th>
              <th>Action</th>
            </thead>  
            <tbody>

              @foreach($paymentMethods as $paymentMethod)
                <tr>
                  <td>{{ $paymentMethod->Name }}</td>
                  <td>
                    @if($paymentMethod->Type === 'CREDIT_CARD')
                      <i class="fa fa-check"></i>
                    @else

                    @endif
                  </td>
                  <td>
                                        
                    <a id="edit" href="{{ url()->current() }}/{{ $paymentMethod->Id }}/edit" class="btn btn-xs btn-default"> 
                      <i class="fa fa-edit"></i>
                      Edit 
                    </a>
                    
                  {{--   <form action="{{ action('QuickBooks\PaymentMethodController@destroy', $paymentMethod->Id) }}" method="post" style="display: inline;">
                      @csrf
                      <input name="_method" type="hidden" value="DELETE">
                      <button class="btn btn-xs btn-default" type="submit">
                        <i class="fa fa-trash"></i>
                        Delete
                      </button>
                    </form> --}}


                  </td>
                </tr>
              @endforeach

            </tbody>
          </table>
            
        </div>

      </div>
      </div>
    </div>
@endsection

@push('after_scripts')
  <script>
    $("a#delete").click(function (e) {
      let form = document.createElement('form');
      form.action = $(this).attr('href');
      form.method = 'post';

      form.innerHTML = '@csrf';
      form.innerHTML = '<input type="hidden" name="_method" value="DELETE">';

      // the form must be in the document to submit it
      document.body.append(form);

      form.submit();
    })
  </script>
@endpush