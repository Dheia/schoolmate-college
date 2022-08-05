@extends('backpack::layout_student')

@section('header')
<style>
      @media only screen and (min-width: 768px) {
        .content-wrapper{
      border-top-left-radius: 50px;
      }
      .sidebar-toggle{
        margin-left:40px;
      }
     
    }
    .main-footer{
      border-bottom-left-radius: 50px;
    
    }
    .card-status{
      margin:10px;
      background-color: #1CC883;
      border-radius: 5px;
      margin-bottom:0px;
      width: 100px;
      height: 35px;

    }
    .card{
      margin-bottom: 10px;
      background-color: #fff;
      border-radius: 13px;
      box-shadow: 0 4px 6px 0 rgba(0,0,0,0.2);
      width: 100%;
   
    }
    .card:hover{
      margin-left: -5px;
      box-shadow: 0 4px 6px 0 #3C8DBC;
      width: 102%;
      
    }
    .card-info{
      border-radius: 13px;
      width: 100%;
      border: 1px solid rgb(238, 232, 232);
    }
    .row-quiz{
      display: flex;
      flex-direction: row;
    }
    .column-right {
 
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      
    }
    .column-left {
      margin-left:20px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      
    }
    .column-description{
      margin-left:20px;
      margin-right:20px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
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
    .box{
      flex-direction: column;
    }
  </style>
@endsection

@section('content')
  <link rel="stylesheet" type="text/css" href="{{asset('css/onlineclass/class.css')}}">
  
  <body style="background: #3c8dbc;">
  <div class="row p-l-30 p-r-30">
    <div class="col-md-8 col-lg-8 no-padding">
    @include('student/online-class/partials/navbar')
   
      
        <div class="col-md-12 col-lg-12 ">
                <show-quiz-result 
                online_quiz_id="{{ $classQuizzes }}" > </show-quiz-result>
        </div>
       
      <!-- END OF QUIZ LIST -->
    </div>

    <div class="col-md-4 col-lg-4 col-two">
      <!-- START RIGHT SIDE BAR -->
      
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
                       <div class="box shadow p-0">
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
                                     {{ Str::limit($my_class->subject->subject_title ? $my_class->subject->subject_title : '-', 30)  }}
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
      <!-- END RIGHT SIDE BAR -->
    </div>
    
  </div>
  </body>
  

@endsection
@section('after_scripts')
    <script type="text/javascript">
    document.getElementById("nav-quiz").classList.add("active");
  </script>
	{{-- VUE JS --}}
	<link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/onlineclass/quiz.js') }}"></script>
	<script>

	</script>
@endsection
