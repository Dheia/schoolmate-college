{{-- ENROLLMENT DATA --}}
<div class="row">
   
    <div class="col-md-4 col-sm-12 col-xs-12">
      <div class="info-box shadow">
        <div class="box-default">
          <div class="box-header with-border">
            {{-- <h3 class="box-title">Enrolment Data for S.Y. {{ $school_year ?? null }} <br>as of {{\Carbon\Carbon::today()->format('m-d-Y') }}</h3> --}}
            <h3 class="box-title">Enrolment Data for S.Y. {{ $school_year ?? null }}</h3>
          </div>
          <div class="box-body p-l-30 p-r-30">

            <div class="progress-group">
              <span class="progress-text">Total Enrolment: </span>
              <span class="progress-number"><b>{{$enrolment_data_total->count()}} </b></span>
            </div>
            <div class="progress-group">
              <span class="progress-text indented">Male: </span>
              <span class="progress-number"><b>{{$enrolment_data_total->pluck('student')->where('gender', 'Male')->count()}} </b></span>
            </div>
            <div class="progress-group">
              <span class="progress-text indented">Female: </span>
              <span class="progress-number"><b>{{$enrolment_data_total->pluck('student')->where('gender', 'Female')->count()}} </b></span>
            </div>
            <hr>


            @if(count($enrolment_by_level) > 0)
              @foreach($enrolment_by_level as $edata)
                {{-- <div class="col-md-12 col-sm-12 col-xs-12"> --}}

                <div class="progress-group">
                  <span class="progress-text">{{$edata->level["year"]}}</span>
                  <span class="progress-number"><b>{{$edata->level_total}}</b></span>
                </div>

                {{-- </div> --}}
              @endforeach
            @endif

          </div>
        </div>
      </div>
    </div>

    <div class="col-md-8 col-sm-12 col-xs-12">
      <div class="info-box shadow">
        @if(backpack_user()->hasRole('Admission'))
          <div class="box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Enrolment Data for S.Y. {{ $school_year ?? null }} as of {{\Carbon\Carbon::today()->format('m-d-Y') }}</h3>
            </div>
            
            <div class="box-body">
             
              <div class="col-md-12">

                <div class="row" style="margin-bottom: 10px;">
                  

                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-8 col-md-offset-2">
                      <div id="canvas-holder">
                        <canvas id="chart-area" width="200" height="250" />
                      </div>
                    </div>

                    {{-- <div class="col-md-7">
                      <div id="canvas-holder">
                        <canvas id="chart-area-departmental" height="300"/>
                      </div>
                    </div> --}}
                  </div>

                </div>
        
              </div>

            </div>

          </div> <!-- .box-default -->
        @endif
      </div>
    </div>

    

  </div>


    <script>
      var myData = {{$rawChartDS}};
      var myLabel = {!!$rawChartLabel!!};
      
      var config = {
          type: 'doughnut',
          data: {
            datasets: [{
              data: myData,
              backgroundColor: 
                palette('tol-dv', myData.length).map(function(hex) {
                return '#' + hex;
                })
              ,
              // label: 'Dataset 1'
            }],
            labels: myLabel
          },
          options: {
            responsive: true,
            title: {
              display: true,
              text: 'Population by Level'
            }
          },
          showTooltips: true,
          pieceLabel: {
            render: 'label',
            segment: true,
          }
        };
        var posSalesCanvas      = document.getElementById('chart-area');
        var ctx                 = posSalesCanvas.getContext("2d");
        var posSalesChart       = new Chart(posSalesCanvas, config);
    </script>

  