  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand {{ config($styleDefine.'.main-header') }}">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      
      @if (is_array(config('vncore-config.admin.module_header_left')))
        @foreach (config('vncore-config.admin.module_header_left') as $module)
          @includeIf($module)
        @endforeach
      @endif

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <a class="nav-link" href="{{ vncore_route_admin('home') }}" target=_new>
        <i class="fas fa-home"></i>
      </a> 

      @if (is_array(config('vncore-config.admin.module_header_right')))
        @foreach (config('vncore-config.admin.module_header_right') as $module)
          @includeIf($module)
        @endforeach
      @endif

    </ul>
  </nav>
  <!-- /.navbar -->
