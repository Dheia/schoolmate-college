@if (auth()->check())
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        @include('backpack::inc.sidebar_parent_panel')

        
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <div class="sidebar-form" style="position: relative; overflow: unset; margin-bottom:40px;">
          <div class="input-group">
            <input type="text" name="q" id="search_sidebar" class="form-control" placeholder="Search..." autocomplete="off">
            <span class="input-group-btn">
              <a href="javascript:void(0)" name="search" class="btn btn-flat">
                <i class="fa fa-search"></i>
              </a>
            </span>
            <ul class="search-results">
              {{-- <li><a href="index.html">Search Result #1<br /><span>Description...</span></a></li> --}}
            </ul>
          </div>

          {{-- <div class="search-content" > --}}

          {{-- </div> --}} 
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
          {{-- <li class="header">{{ trans('backpack::base.administration') }}</li> --}}
          <!-- ================================================ -->
          <!-- ==== Recommended place for admin menu items ==== -->
          <!-- ================================================ -->

          @include('backpack::inc.sidebar_content_parent')

          <!-- ======================================= -->
          {{-- <li class="header">Other menus</li> --}}
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>
@endif
