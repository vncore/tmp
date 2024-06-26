   <!-- Main Sidebar Container -->
   <aside class="main-sidebar sidebar-light-pink elevation-4 sidebar-no-expand">
    <!-- Brand Logo -->
    <a href="{{ vc_route_admin('admin.home') }}" class="brand-link navbar-secondary"">
      {!! vc_config_admin('ADMIN_LOGO') !!}
    </a>

    <!-- Sidebar -->
    <div class="sidebar {{ config($styleDefine.'.sidebar') }}">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-legacy" data-widget="treeview" role="menu" >
        @php
          $menus = Admin::getMenuVisible();
        @endphp

@if (count($menus))
{{-- Level 0 --}}
      @foreach ($menus[0] as $level0)
        {{-- LEvel 1  --}}
        @if (!empty($menus[$level0->id]))
        <li class="nav-link header">
          <i class="nav-icon  {{ $level0->icon }} "></i> 
          <p class="sub-header"> {!! vc_language_render($level0->title) !!}</p>
        </li>
          @foreach ($menus[$level0->id] as $level1)
            @if($level1->uri)
            <li class="nav-item {{ \Admin::checkUrlIsChild(url()->current(), vc_url_render($level1->uri)) ? 'active' : '' }}">
              <a href="{{ $level1->uri?vc_url_render($level1->uri):'#' }}" class="nav-link">
                <i class="nav-icon {{ $level1->icon }}"></i>
                <p>
                  {!! vc_language_render($level1->title) !!}
                </p>
              </a>
            </li>
            @else

          {{-- LEvel 2  --}}
          @if (!empty($menus[$level1->id]))
          <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon  {{ $level1->icon }} "></i>
                <p>
                  {!! vc_language_render($level1->title) !!}
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>

              <ul class="nav nav-treeview">
                @foreach ($menus[$level1->id] as $level2)
                  @if($level2->uri)
                  <li class="nav-item {{ \Admin::checkUrlIsChild(url()->current(), vc_url_render($level2->uri)) ? 'active' : '' }}">
                    <a href="{{ $level2->uri?vc_url_render($level2->uri):'#' }}" class="nav-link">
                      <i class="{{ $level2->icon }} nav-icon"></i>
                      <p>{!! vc_language_render($level2->title) !!}</p>
                    </a>
                  </li>
                  @else

                {{-- LEvel 3  --}}
                @if (!empty($menus[$level2->id]))
                  <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                      <i class="nav-icon  {{ $level2->icon }} "></i>
                      <p>
                        {!! vc_language_render($level2->title) !!}
                        <i class="right fas fa-angle-left"></i>
                      </p>
                    </a>

                  <ul class="nav nav-treeview">
                    @foreach ($menus[$level2->id] as $level3)
                      @if($level3->uri)
                        <li class="nav-item {{ \Admin::checkUrlIsChild(url()->current(), vc_url_render($level3->uri)) ? 'active' : '' }}">
                          <a href="{{ $level3->uri?vc_url_render($level3->uri):'#' }}" class="nav-link">
                            <i class="{{ $level3->icon }} nav-icon"></i>
                            <p>{!! vc_language_render($level3->title) !!}</p>
                          </a>
                        </li>
                      @else
                      <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                          <i class="nav-icon  {{ $level3->icon }} "></i>
                          <p>
                            {!! vc_language_render($level3->title) !!}
                            <i class="right fas fa-angle-left"></i>
                          </p>
                        </a>
                      </li>
                      @endif
                    @endforeach
                  </ul>                    
                  </li>
                  @endif
                  {{-- end level 3 --}}

                  @endif
                @endforeach
              </ul>
              </li>
            @endif
            {{-- end level 2 --}}

            @endif
          @endforeach
        {{--  end level 1 --}}

          @endif
        @endforeach
      {{-- end level 0 --}}
      @endif

      </ul>

      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  