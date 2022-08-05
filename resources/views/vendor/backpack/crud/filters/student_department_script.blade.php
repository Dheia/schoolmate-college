@push('crud_list_scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/URI.js/1.18.2/URI.min.js" type="text/javascript"></script>
    <script>
      var department = "{{ request()->get('department') }}";
      var school_year = "{{ request()->get('school_year_id') }}";
      @php
        if(request()->get('school_year_id'))
        {
          $url = $crud->route.'?school_year_id='.request()->get('school_year_id').'&department='.request()->get('department');
        }
        else
        {
          $url = $crud->route.'?department='.request()->get('department');
        }
      @endphp
      // button to remove all filters
      jQuery(document).ready(function($) {
        $("#remove_filters_button").click(function(e) {
          console.log(department);
          console.log(school_year);
          e.preventDefault();
          // behaviour for ajax table
          var new_url = '{{ url($crud->route.'/search') }}';
          var ajax_table = $("#crudTable").DataTable();
          // replace the datatables ajax url with new_url and reload it
          ajax_table.ajax.url(new_url).load();
          // clear all filters
          $(".navbar-filters li[filter-name]").trigger('filter:clear');
          // remove filters from URL
          window.location.href = "{!!url($url)!!}";
          crud.updateUrl(new_url);
        });
        // hide the Remove filters button when no filter is active
        $(".navbar-filters li[filter-name]").on('filter:clear', function() {
          var anyActiveFilters = false;
          $(".navbar-filters li[filter-name]").each(function () {
            if ($(this).hasClass('active')) {
              anyActiveFilters = true;
              console.log('ACTIVE FILTER');
            }
          });
          if (anyActiveFilters == false) {
            $('#remove_filters_button').addClass('hidden');
          }
        });
      });
    </script>

    <script>
      jQuery(document).ready(function($) {
        @php
          $selected_term = request()->term;
          $selected_track = request()->track_id;
        @endphp 
        $('#selected_term').html("{{$selected_term ? $selected_term : '-'}}");
        $('#selected_track').html("{{$selected_track ? $selected_track : '-'}}");

      // Term On Change Display Selected Term
      $("select[name=filter_term]").change(function() {
        var value = $(this).val();
        $('#selected_term').html(value);
      
      });
      // Term On Change Display Selected Term
      $("select[name=filter_track_id]").change(function() {
        var value = $(this).val();
        $('#selected_track').html(value);
      
      });
    });
    </script>
@endpush