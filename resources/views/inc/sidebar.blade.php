<nav class="page-sidebar" data-pages="sidebar">
    <!-- BEGIN SIDEBAR MENU TOP TRAY CONTENT-->
    <!-- END SIDEBAR MENU TOP TRAY CONTENT-->
    <!-- BEGIN SIDEBAR MENU HEADER-->
    <div class="sidebar-header">
        <img src="/img/cecoworking.png" alt="logo" class="brand"
            data-src="/img/cecoworking.png" data-src-retina="/img/cecoworking.png"
            width="92%">
    </div>
    <!-- END SIDEBAR MENU HEADER-->
    <!-- START SIDEBAR MENU -->
    <div class="sidebar-menu">
        <!-- BEGIN SIDEBAR MENU ITEMS-->
        <ul class="menu-items">
            <li class="m-t-10 {{ request()->is('/') ? 'active' : '' }}">
                <a href="/{{ request()->slug_instalacion }}/admin/" class="detailed">
                    <span class="title">Inicio</span>
                </a>
                <span class="icon-thumbnail"><i data-feather="home"></i></span>
            </li>
            <li class="{{ request()->is(request()->slug_instalacion . '/admin/pistas*') ? 'active' : '' }}">
                <a href="/{{ request()->slug_instalacion }}/admin/pistas" class="detailed">
                    <span class="title">Espacios</span>
                </a>
                <span class="icon-thumbnail"><i class="material-icons sports_tennis">&#xea32;</i></span>
            </li>
            <li class="{{ request()->is(request()->slug_instalacion . '/admin/users*') ? 'active' : '' }}">
                <a href="/{{ request()->slug_instalacion }}/admin/users" class="detailed">
                    <span class="title">Usuarios</span>
                </a>
                <span class="icon-thumbnail"><i data-feather="users"></i></span>
            </li>
            <li class=" {{ request()->is(request()->slug_instalacion . '/admin/configuracion*') ? 'open active' : '' }}">
                <a href="javascript:;"><span class="title">Configuracion</span>
                <span class="arrow {{ request()->is(request()->slug_instalacion . '/admin/configuracion*') ? 'open active' : '' }}"></span></a>
                <span class="icon-thumbnail"><i data-feather="settings"></i></span>
                <ul class="sub-menu" style=" {{ request()->is(request()->slug_instalacion . '/admin/configuracion*') ? 'display:block' : '' }}">
                  <li class="{{ request()->is(request()->slug_instalacion . '/admin/configuracion/instalacion') ? 'active' : '' }}">
                    <a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion">Instalación</a>
                    <span class="icon-thumbnail">in</span>
                  </li>
                  <li class="{{ request()->is(request()->slug_instalacion . '/admin/configuracion/pistas-reservas') ? 'active' : '' }}">
                    <a href="/{{ request()->slug_instalacion }}/admin/configuracion/pistas-reservas">Pistas y reservas</a>
                    <span class="icon-thumbnail">pi</span>
                  </li>
                </ul>
              </li>
            {{-- <li class="{{ request()->is(request()->slug_instalacion . '/admin/configuracion*') ? 'active' : '' }}">
                <a href="/{{ request()->slug_instalacion }}/admin/configuracion" class="detailed">
                    <span class="title">Configuracion</span>
                </a>
                <span class="icon-thumbnail"><i data-feather="settings"></i></span>
            </li> --}}
            <li>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="detailed">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <span class="title">Logout</span>
                </a>
                <span class="icon-thumbnail"><i data-feather="power"></i></span>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <!-- END SIDEBAR MENU -->
</nav>