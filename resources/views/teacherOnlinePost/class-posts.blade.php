@extends('backpack::layout')

@section('header')
@endsection

@push('after_styles')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/onlineclass/class.css') }}">
  <style>

    .row-profile{
      display: flex;
      flex-direction: row;
      width: 100%;
    }
    .row-text{
      display: flex;
      flex-direction: row;
      width: 100%;
    }
    .column-text-right{
      display: flex;
    flex-direction: column;
    flex-basis: 100%;
    flex: 1;
    width: 100%;
    align-items: flex-end;
    }
    .column-text-left{
    display: flex;
    flex-direction: column;
    flex-basis: 100%;
    flex: 1;
    width: 100%;
    align-items: flex-start;
    }
  
    .column-profile-name{
      display: flex;
      flex-direction: column;
      width: 100%;
      margin-left: 10px;
    }

     .card{
      margin-bottom: 10px;
      background-color: #E6E6F3;
      border-radius: 13px;
      display: flex;
      flex-direction: row;
      width: 100%;

    }
    .column {
      display: flex;
      flex-direction: column;
      flex-basis: 100%;
      flex: 1;
      width: 100%;
      align-items: flex-start;
      
    }
    .column-icon {
      display: flex;
      flex-direction: column;
      flex-basis: 40%;
      flex: 0 0 60px;
      padding: 7px;
      padding-left: 13px;
      width: 90%;
    }
 
    .card-icon{
      background-color: #fff;
      border-radius: 10px;
      width: 40px;
      height: 40px;
    }
    .column-profile-pic{
        display: flex;
        flex-direction: column;
      }
    .font-serif-bold{
      font-family:Arial,Helvetica,sans-serif;
      font-size: 13px;
      color:rgb(27, 27, 27);
      font-weight: bold;
    }
    .auto-center{
      margin-top:auto;
      margin-bottom: auto;
    }
    .profile-pic{
      max-width: 65px;
      width: 100%;
      border-radius: 100%;
      overflow: hidden;
      padding: 0px;
      margin: 0px;
      border: 1.5px #d2d6de solid;
      border-radius: 100%;
    }
    .profile-pic:hover{
      border: 1.5px #3C8DBC solid;
    }
    .btn_comments{
      border: 1px #2c3758 solid;
    }

    
    @media only screen and (min-width: 768px) {
      /* For desktop: */
    .content-wrapper{
        border-top-left-radius: 50px;
        }
      .sidebar-toggle{
        margin-left:30px;
      }
    }
    .main-footer{
      border-bottom-left-radius: 50px;
    
    }
  
  </style>
@endpush

@section('content')  

  <div class="row p-l-20 p-r-20">
    @include('onlineClass/partials/navbar')
  </div>

  <div class="row p-l-20 p-r-20">

    <!-- START RIGHT SIDEBAR -->
    @include('onlineClass/partials/right_sidebar')
    <!-- END RIGHT SIDEBAR -->

    <!-- START OF MAIN FEED -->  
    <div class="col-md-8 col-lg-8 col-one">
      <div class="">
        <div class="info-box add-post shadow">
          <div class="box-body">
            <!-- <div class="row"> -->
            <div class="form-group col-2 col-xs-2 col-sm-2 col-md-1 col-lg-1 p-t-10" style="margin: 0px;">
              <a class="thumbnail form-control" >
                @if($user->employee->photo)
                  @if(file_exists($user->employee->photo))
                  <img src="{{ asset($user->employee->photo) }}" alt="...">
                  @else
                  <img src="{{ asset('images/headshot-default.png') }}" alt="...">
                  @endif
                @else
                  <img src="{{ asset('images/headshot-default.png') }}" alt="...">
                @endif
              </a>
            </div>
            <div class="form-group col-8 col-xs-8 col-sm-8 col-md-10 col-lg-10 p-t-10" style="margin: 0px;">
              <input style="border: none; margin-top: 5px;" 
                placeholder="Start a discussion, add an assignment, create a quiz..." 
                class="form-control" data-toggle="modal" data-target="#formModal" id="createInput">
            </div>
            <div class="form-group col-2 col-xs-2 col-sm-2 col-md-1 col-lg-1 p-t-10" style="margin: 0px; padding: 0px;">
              <button type="button" class="btn btn-primary btn-circle" style="height: 50px; width: 50px; border-radius: 50%; margin-right: 5px;" data-toggle="modal" data-target="#formModal">
                <i class="fa fa-plus"></i>
              </button>
            </div>
          </div>
          <!-- </div> -->
        </div>
      </div>
      <!-- START SECTION CLASS POSTS -->
      <class-posts-teacher :school_id="'{{ env('SCHOOL_ID') }}'" :user="{{ $user }}"
        :spaces_url="'{{ env('DO_SUBDOMAIN') }}'" :code="{{ json_encode($class->code) }}">
      </class-posts-teacher>
      <!-- END SECTION CLASS POSTS -->
    </div>
    <!-- END OF MAIN FEED -->

  </div>
  <!-- START CREATE FORM MODAL -->
  <div id="formModal" class="modal fade" role="dialog"  style="border-radius: 5px;">
    <div class="modal-dialog">
      <form style="border-radius: 5px;" method="post" action="{{ url($crud->route . '?class_code=' . $class->code) }}" @if ($crud->hasUploadFields('create')) enctype="multipart/form-data" @endif >
        {!! csrf_field() !!}
        <div class="box" style="border-radius: 5px; border: none; box-shadow: none !important;">
          <div class="box-body" style="padding: 0px; border: none; box-shadow: none !important;">
            
              <div class="col-md-12" style="border-radius: 5px; border: none;">

                <div class="row display-flex-wrap" style="padding-bottom: 0px; border: none; box-shadow: 0 0 0 rgba(0,0,0,0);" >
                  <!-- load the view from the application if it exists, otherwise load the one in the package -->
                  @if(view()->exists('vendor.backpack.crud.form_content'))
                    @include('vendor.backpack.crud.form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
                  @else
                    @include('crud::form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
                  @endif

                </div><!-- /.box-body -->
                <div class="">
                   
                </div><!-- /.box-footer-->

              </div><!-- /.box -->
            
          </div>
          <div class="box-footer" style="padding: 0px 10px; border-radius: 5px; border: none; box-shadow: 0 0 0 rgba(0,0,0,0);">
            <div class="pull-right" style="padding-left: 20px;">
                 <button style="margin-right: 10px;" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button id="btnPost" style="margin-right: 10px;" type="submit" class="btn btn-primary pull-right" >Post</button>
              </div>
        </div>
        </div>
      </form>
    </div>
  </div>
  <!-- END CREATE FORM MODAL -->
@endsection

@push('before_scripts')
   {{-- VUE JS --}}
  <script src="{{ mix('js/onlineclass/newsfeed.js') }}"></script>
@endpush

@push('after_scripts')
  <script type="text/javascript">
    document.getElementById("nav-explore").classList.add("active");
  </script>
@endpush
