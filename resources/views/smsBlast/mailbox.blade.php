<div class="col-md-12" style="max-width: 800px;">
  <div class="row">
  <div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">{{ $entry->title }}</h3>

    <div class="box-tools pull-right">
      <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="" data-original-title="Previous"><i class="fa fa-chevron-left"></i></a>
      <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="" data-original-title="Next"><i class="fa fa-chevron-right"></i></a>
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body no-padding">
    <div class="mailbox-read-info">
    
      {{-- <h3>{{ $entry->title }}</h3> --}}
      <h5>To:

          <span class="mailbox-read-time pull-right">{{ $entry->send_date_time }}</span>
        </h5>
    </div>
    <div class="mailbox-read-message col-md-12" style="overflow-x: scroll;">
     {{ $entry->message }}
    </div>
    <!-- /.mailbox-read-message -->
  </div>

</div></div>
</div>
