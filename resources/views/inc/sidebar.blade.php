<nav class="page-sidebar" data-pages="sidebar">
    <!-- BEGIN SIDEBAR MENU TOP TRAY CONTENT-->
    <!-- END SIDEBAR MENU TOP TRAY CONTENT-->
    <!-- BEGIN SIDEBAR MENU HEADER-->
    <div class="sidebar-header">
        <img src="/simply_white/assets/img/logo.png" alt="logo" class="brand"
            data-src="/simply_white/assets/img/logo.png" data-src-retina="simply_white/assets/img/logo_2x.png"
            width="78" height="22">
    </div>
    <!-- END SIDEBAR MENU HEADER-->
    <!-- START SIDEBAR MENU -->
    <div class="sidebar-menu">
        <!-- BEGIN SIDEBAR MENU ITEMS-->
        <ul class="menu-items">
            <li class="m-t-10 {{ request()->is('/') ? 'active' : '' }}">
                <a href="/admin/" class="detailed">
                    <span class="title">Inicio</span>
                </a>
                <span class="icon-thumbnail"><i data-feather="home"></i></span>
            </li>
            <li class="{{ request()->is('pistas*') ? 'active' : '' }}">
                <a href="/admin/pistas" class="detailed">
                    <span class="title">Pistas</span>
                </a>
                <span class="icon-thumbnail"><i class="material-icons sports_tennis">&#xea32;</i></span>
            </li>
            <li class="{{ request()->is('users*') ? 'active' : '' }}">
                <a href="/admin/users" class="detailed">
                    <span class="title">Usuarios</span>
                </a>
                <span class="icon-thumbnail"><i data-feather="users"></i></span>
            </li>
            <li class="{{ request()->is('configuracion*') ? 'active' : '' }}">
                <a href="/admin/configuracion" class="detailed">
                    <span class="title">Configuracion</span>
                </a>
                <span class="icon-thumbnail"><i data-feather="settings"></i></span>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <!-- END SIDEBAR MENU -->
</nav>