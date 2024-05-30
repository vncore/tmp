  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand {{ config($styleDefine.'.main-header') }}">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      
        @include($templatePathAdmin.'component.language')
        @include($templatePathAdmin.'component.admin_theme')
        @isset($templatePathSpmo)
          @includeIf($templatePathSpmo.'component.partner')
        @endisset

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <a class="nav-link" href="{{ sc_route_admin('home') }}" target=_new>
        <i class="fas fa-home"></i>
      </a> 

      @include($templatePathAdmin.'component.notice')

      @include($templatePathAdmin.'component.admin_profile')


      {{-- <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li> --}}

    </ul>
  </nav>
  <!-- /.navbar -->
