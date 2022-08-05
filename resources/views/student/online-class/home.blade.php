@extends('backpack::layout_student')

@section('header')
@endsection

@section('content')
<body style="background: #3c8dbc;">
  <link rel="stylesheet" type="text/css" href="{{asset('css/onlineclass/class.css')}}">
  <div class="row p-l-30 p-r-30">
    <div class="col-md-8 col-lg-8 no-padding" style="border-radius: 5px;">
      @include('student/online-class/partials/navbar')
   
    <div class="col-md-12 col-lg-12 no-padding" style="border-radius: 5px;">

      <!-- START POSTS FORM -->
      {{-- <div class="">
        <div class="box shadow">
          <div class="box-body with-border" style="padding: 10 20px !important;">
            <div class="row" style="padding-bottom: 0;">
              <div class="form-group col-2 col-xs-2 col-sm-2 col-md-1 col-lg-1" style="margin: 0px;">
                <a class="thumbnail form-control" style="height: 50px; width: 50px; border-radius: 50%; overflow: hidden; padding: 0px; margin: 0px;">
                  @if($user->photo)
                    @if(file_exists($user->photo))
                    <img src="{{ asset($user->photo) }}" alt="...">
                    @else
                    <img src="{{ asset('images/headshot-default.png') }}" alt="...">
                    @endif
                  @else
                    <img src="{{ asset('images/headshot-default.png') }}" alt="...">
                  @endif
                </a>
              </div>
              <div class="form-group col-8 col-xs-8 col-sm-8 col-md-10 col-lg-10" style="margin: 0px;">
                  <input style="border: none; margin-top: 5px;" 
                    placeholder="Start a discussion, add an assignment, create a quiz..." 
                    class="form-control" data-toggle="modal" data-target="#formModal">
              </div>
              <div class="form-group col-2 col-xs-2 col-sm-2 col-md-1 col-lg-1" style="margin: 0px; padding: 0px;">
                <button type="button" class="btn btn-primary btn-circle" style="height: 50px; width: 50px; border-radius: 50%; margin-right: 5px;" data-toggle="modal" data-target="#formModal">
                  <i class="fa fa-plus"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div> --}}
      <!-- END POSTS FORM -->

      <!-- START SECTION CLASS POSTS -->
      <student-explore :school_id="'{{ env('SCHOOL_ID') }}'" :user="{{ $user }}" :user_id="{{ $user->id }}" 
        :submitted_quiz="{!! json_encode($studentSubmittedQuiz) !!}" :spaces_url="'{{ env('DO_SUBDOMAIN') }}'">
      </student-explore>
      <!-- END SECTION CLASS POSTS -->
      
    </div>
    </div>

    <div class="col-md-4 col-lg-4">
      <!-- START RIGHT SIDE BAR -->
      {{-- <div class="box shadow">
        <div class="box-header with-border m-b-10" style="padding: 10px;">
            <h4 class="" style="padding: 0px !important; margin: 0px !important">
              My Classes
            </h4>
        </div>
        @if($my_classes)
          @if(count($my_classes)>0)
            @foreach($my_classes as $class)
              <div class="box-body with-border" style="padding: 0px 10px 10px 10px;">
                <div class="col-md-1 col-xs-1">
                  <span class="circle-span" style="background-color:{{ $class->color }};"></span>
                </div>
                <div class="col-md-10 col-xs-10">
                  <h5 class="" style="padding: 0px !important; margin: 0px !important">
                    <a href="{{ url($crud->route)}}?class_code={{$class->code }}">
                      {{ $class->name }}
                    </a>
                  </h5>
                </div>
              </div>
            @endforeach
          @endif
        @endif
        <div class="box-footer with-border br-b-15" style="padding: 10px;">
          <a href="{{ asset('student/online-class') }}">View all classes</a>
        </div>
      </div> --}}
      <!-- END RIGHT SIDE BAR -->
      
      <div class="col-md-12 col-lg-12 p-l-20">
        <div class="row-text">
          <div class="column-text-left" >
             <h3 class="oc-header-title " style="font-size:19px; color:#2683B9;"><b>My Classes</b> </h3>
          </div>
            <div class="column-text-right" style="align-items: right">
              <a href="{{ asset('student/online-class') }}">
                 <h3 class="oc-header-title" style="font-size:14px; color:#a5a5a5;"><b>View all</b> </h3>
              </a>
            </div>
        </div>
      </div>

     <div class="col-md-12 col-lg-12 oc">
       <div class="" style="">
         <div class="" style="">
           @if($my_classes)
             @if(count($my_classes)>0)
               @php
                 $class_count = 0;
               @endphp
               @foreach($my_classes as $my_class)

                 @if($class_count%2 == 0)
                 @endif
                  <div class="row">
                   <div class="col-xs-12 col-md-12 col-lg-12">
                       <div class="box shadow">
                         <div class="box-body" style="padding: 20px !important;">
                           <span class="dot" style="position: absolute; z-index: 999; height: 55%; background-color:{{ $my_class->color }};"></span>
                           <div class="row">
                             <div class="">
                               
                               <!-- Right Circle -->
                               <span class="dot" style="right: 10px; width: 10px; height: 10px; background-color: {{$my_class->ongoing ? '#1cc88a' : '#e1e1e1'}};"></span>

                               <!-- Subject Code and Class Code -->
                               <h6 class = "class-desc"> 
                                   {{ $my_class->subject_code }}
                               </h6>

                               <!-- Class Name -->
                               <a href="{{ asset('student/online-post?class_code='.$my_class->code) }}">
                                 @if($my_class->subject)
                                   <h4 class="class-header">
                                     {{ $my_class->subject->subject_title ? $my_class->subject->subject_title : '-' }}
                                     {{ $my_class->summer ? '(Summer)' : '' }}
                                   </h4>
                                 @else
                                   <h4>Unknown Subject {{ $my_class->summer ? '(Summer)' : '' }} </h4>
                                 @endif
                               </a>

                               <!-- Class Teacher -->
                               <h6 class = "class-desc">
                                 {{ $my_class->teacher ? $my_class->teacher->prefix . '. ' .  $my_class->teacher->full_name  : 'Unknown Teacher' }}
                               </h6>

                               <!-- Grade and Section -->
                               @if($my_class->section)
                                 <h6 class = "class-desc">
                                     {{ $my_class->section->name_level }} | {{ $my_class->section->track_code }}
                                 </h6>
                               @endif

                               <!-- Video Conference Status -->
                               {{-- <h5 style="padding: 5px 15px 0px 30px; margin-top: 0px; margin-bottom: 0px;">
                                 <span id="video_conference" class="badge {{ $my_class->conference_status ?  'label-success' : 'label-default' }} smo-vc">
                                   <i class="fa fa-video-camera"></i> 
                                   {{ $my_class->conference_status ?  'Video Conference On-going' : 'No On-going Video Conference' }}  
                                 </span>
                               </h5> --}}
                               <h5 style="padding: 5px 15px 0px 30px; margin-top: 0px; margin-bottom: 0px;">
                                 <span id="video_conference" class="badge {{ $my_class->ongoing ?  'label-success' : 'label-default' }} smo-vc">
                                   <i class="fa fa-video-camera"></i> 
                                   {{ $my_class->ongoing ?  'Class is on going' : 'No On-going Class' }}  
                                 </span>
                               </h5>

                             </div>
                           </div>
                         </div>
                       </div>
                   </div>

                  </div>
                 @if($class_count%2 != 0)   
                 @elseif($loop->last)
                   </div>
                 @endif
                 
                 @php
                 if($class_count == 3){
                   break;
                 }else{
                   $class_count =  $class_count + 1;
                 }
                 @endphp
               @endforeach
             @else
               <div>
                 <div class="col-xs-12 col-md-12 col-lg-12">
                   <div class="box" style="border-radius: 5px;">
                     <div class="box-body" style="padding: 10px; height: 120px;">
                       <div class="text-center" style="margin-top: 40px !important;">
                         <h3 class="text-center">
                           No available class
                         </h3>
                       </div>
                     </div>
                   </div>
                 </div>
               </div>
             @endif
           @else
             <div>
               <div class="col-xs-12 col-md-12 col-lg-12">
                 <div class="box" style="border-radius: 5px;">
                   <div class="box-body" style="padding: 10px; height: 120px;">
                     <div class="text-center" style="margin-top: 40px !important;">
                       <h3 class="text-center">
                         No available class
                       </h3>
                     </div>
                   </div>
                 </div>
               </div>
             </div>
           @endif
         </div>
       </div>
     <!-- END SECTION CLASS POSTS -->

    </div>
  </div>
  <!-- START CREATE FORM MODAL -->
  {{-- <div id="formModal" class="modal fade" role="dialog"  style="border-radius: 10px;">
    <div class="modal-dialog">
      <form style="border-radius: 10px;" method="post" action="{{ url($crud->route) }}" @if ($crud->hasUploadFields('create')) enctype="multipart/form-data" @endif >
        {!! csrf_field() !!}
        <div class="box" style="border-radius: 10px; border: none; box-shadow: 0 0 0 rgba(0,0,0,0);">
          <div class="box-body" style="padding: 0px; border: none; box-shadow: 0 0 0 rgba(0,0,0,0);">
            
              <div class="col-md-12" style="border-radius: 10px; border: none;">

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
          <div class="box-footer" style="padding: 0px 10px; border-radius: 10px; border: none; box-shadow: 0 0 0 rgba(0,0,0,0);">
            <div class="box" style="border-radius: 10px; border: none; box-shadow: 0 0 0 rgba(0,0,0,0);">
            <div class="row" style="padding: 0px 10px;">
              
              <div class="pull-right" style="padding-left: 20px;">
                 <button style="margin-right: 10px;" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button id="btnPost" style="margin-right: 10px;" type="submit" class="btn btn-primary pull-right" >Post</button>
              </div>
            </div>
          </div>
        </div>
        </div>
      </form>
    </div>
  </div> --}}
  <!-- END CREATE FORM MODAL -->
</body>
@endsection

@section('after_styles')
  <!-- JQUERY CONFIRM -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  <!-- DATA TABLES -->
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">

  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">

  <!-- CRUD LIST CONTENT - crud_list_styles stack -->
  @stack('crud_list_styles')

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
@endsection

@section('after_scripts')
  @include('crud::inc.datatables_logic')

  <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script>

  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
  @stack('crud_list_scripts')
@endsection

@push('after_scripts')
  <script type="text/javascript">
    document.getElementById("nav-explore").classList.add("active");
  </script>
  {{-- VUE JS --}}
  <script src="{{ mix('js/onlineclass/newsfeed.js') }}"></script>
  <!-- JQUERY CONFIRM -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endpush
