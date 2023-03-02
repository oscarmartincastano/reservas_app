<nav class="page-sidebar" data-pages="sidebar">
    <!-- BEGIN SIDEBAR MENU TOP TRAY CONTENT-->
    <!-- END SIDEBAR MENU TOP TRAY CONTENT-->
    <!-- BEGIN SIDEBAR MENU HEADER-->
    <div class="sidebar-header">
        <img src="/img/{{ request()->slug_instalacion }}.png" alt="logo" class="brand"
            data-src="/img/{{ request()->slug_instalacion }}.png"
            data-src-retina="/img/{{ request()->slug_instalacion }}.png" style="max-height: 100px;max-width:92%">

    </div>
    <!-- END SIDEBAR MENU HEADER-->
    <!-- START SIDEBAR MENU -->
    <div class="sidebar-menu">
        <!-- BEGIN SIDEBAR MENU ITEMS-->
        <ul class="menu-items">
            <li class="m-t-10 {{ request()->is(request()->slug_instalacion . '/admin') ? 'active' : '' }}">
                <a href="/{{ request()->slug_instalacion }}/admin/" class="detailed">
                    <span class="title">Inicio</span>
                </a>
                <span class="icon-thumbnail"><i data-feather="home"></i></span>
            </li>
            <li class=" {{ request()->is(request()->slug_instalacion . '/admin/reservas*') ? 'open active' : '' }}">
                <a href="javascript:;"><span class="title">Reservas</span>
                    <span
                        class="arrow {{ request()->is(request()->slug_instalacion . '/admin/reservas*') ? 'open active' : '' }}"></span></a>
                <span class="icon-thumbnail"><i data-feather="book"></i></span>
                <ul class="sub-menu p-0"
                    style=" {{ request()->is(request()->slug_instalacion . '/admin/reservas*') ? 'display:block' : '' }}">
                    <li
                        class="{{ request()->is(request()->slug_instalacion . '/admin/reservas/list') ? 'active' : '' }}">
                        <a href="/{{ request()->slug_instalacion }}/admin/reservas/list">Listado</a>
                        <span class="icon-thumbnail">li</span>
                    </li>
                    <li
                        class="{{ request()->is(request()->slug_instalacion . '/admin/reservas/periodicas') ? 'active' : '' }}">
                        <a href="/{{ request()->slug_instalacion }}/admin/reservas/periodicas">Reservas periódicas</a>
                        <span class="icon-thumbnail">rp</span>
                    </li>
                    <li
                        class="{{ request()->is(request()->slug_instalacion . '/admin/reservas/list/periodicas') ? 'active' : '' }}">
                        <a href="/{{ request()->slug_instalacion }}/admin/reservas/list/periodicas">Listado reservas
                            periodicas</a>
                        <span class="icon-thumbnail">lr</span>
                    </li>
                    <li
                        class="{{ request()->is(request()->slug_instalacion . '/admin/reservas/desactivaciones') ? 'active' : '' }}">
                        <a href="/{{ request()->slug_instalacion }}/admin/reservas/desactivaciones">Desactivaciones
                            periódicas</a>
                        <span class="icon-thumbnail">dp</span>
                    </li>
                </ul>
            </li>
            <li class="{{ request()->is(request()->slug_instalacion . '/admin/pistas*') ? 'active' : '' }}">
                <a href="/{{ request()->slug_instalacion }}/admin/pistas" class="detailed">
                    <span class="title">Espacios</span>
                </a>
                <span class="icon-thumbnail"><i class="material-icons sports_tennis">&#xea32;</i></span>
            </li>
            <li class="{{ request()->is(request()->slug_instalacion . '/admin/users*') ? 'active' : '' }}">
                <a href="/{{ request()->slug_instalacion }}/admin/users" class="detailed">
                    <span class="title" style="position: relative">Usuarios @if (count(auth()->user()->instalacion->users_sin_validar))
                            <mark class="mark">{{ count(auth()->user()->instalacion->users_sin_validar) }}</mark>
                        @endif
                    </span>
                </a>
                <span class="icon-thumbnail"><i data-feather="users"></i></span>
            </li>
            <li class="{{ request()->is(request()->slug_instalacion . '/admin/cobro*') ? 'active' : '' }}">
                <a href="/{{ request()->slug_instalacion }}/admin/cobro" class="detailed">
                    <span class="title">Cobros</span>
                </a>
                <span class="icon-thumbnail"><i data-feather="credit-card"></i></span>
            </li>
            <li
                class=" {{ request()->is(request()->slug_instalacion . '/admin/configuracion*') ? 'open active' : '' }}">
                <a href="javascript:;"><span class="title">Configuracion</span>
                    <span
                        class="arrow {{ request()->is(request()->slug_instalacion . '/admin/configuracion*') ? 'open active' : '' }}"></span></a>
                <span class="icon-thumbnail"><i data-feather="settings"></i></span>
                <ul class="sub-menu"
                    style=" {{ request()->is(request()->slug_instalacion . '/admin/configuracion*') ? 'display:block' : '' }}">
                    <li
                        class="{{ request()->is(request()->slug_instalacion . '/admin/configuracion/instalacion') ? 'active' : '' }}">
                        <a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion">Instalación</a>
                        <span class="icon-thumbnail">in</span>
                    </li>
                    <li
                        class="{{ request()->is(request()->slug_instalacion . '/admin/configuracion/pistas-reservas') ? 'active' : '' }}">
                        <a href="/{{ request()->slug_instalacion }}/admin/configuracion/pistas-reservas">Pistas y
                            reservas</a>
                        <span class="icon-thumbnail">pi</span>
                    </li>
                </ul>
            </li>

            <li class="{{ request()->is(request()->slug_instalacion . '/admin/campos-adicionales') ? 'active' : '' }}">
                <a href="/{{ request()->slug_instalacion }}/admin/campos-adicionales">Campos adicionales en
                    reservas</a>
                <span class="icon-thumbnail"><i data-feather="plus-circle"></i></span>
            </li>

            <li class="{{ request()->is(request()->slug_instalacion . '/admin/patrocinadores') ? 'active' : '' }}">
                <a
                    href="
                {{ route('sponsors.index', ['slug_instalacion' => request()->slug_instalacion]) }}
                ">Patrocinadores</a>
                <span class="icon-thumbnail"><i data-feather="airplay"></i></span>
            </li>

            <li class=" {{ request()->is(request()->slug_instalacion . '/admin/facturas*') ? 'open active' : '' }}">
                <a href="javascript:;"><span class="title">Facturas</span>
                    <span
                        class="arrow {{ request()->is(request()->slug_instalacion . '/admin/facturas*') ? 'open active' : '' }}"></span></a>
                <span class="icon-thumbnail"><i data-feather="book"></i></span>
                <ul class="sub-menu p-0"
                    style=" {{ request()->is(request()->slug_instalacion . '/admin/facturas*') ? 'display:block' : '' }}">

                    {{-- <li class="{{ request()->is(request()->slug_instalacion . '/admin/facturas*') ? 'active' : '' }}">
                        <a
                            href="{{ route('invoices.index', ['slug_instalacion' => request()->slug_instalacion]) }}">Lista</a>
                        <span class="icon-thumbnail">li</span>
                    </li> --}}

                    <li
                        class="{{ request()->is(request()->slug_instalacion . '/admin/facturas/entidades-bancarias') ? 'active' : '' }}">
                        <a href="{{ route('banks.index', ['slug_instalacion' => request()->slug_instalacion]) }}">Entidades
                            bancarias</a>
                        <span class="icon-thumbnail">eb</span>
                    </li>

                    {{-- <li class="{{ request()->is(request()->slug_instalacion . '/admin/facturas*') ? 'active' : '' }}">
                        <a
                            href="{{ route('service-types.index', ['slug_instalacion' => request()->slug_instalacion]) }}">Tipos
                            de servicio</a>
                        <span class="icon-thumbnail">ts</span>
                    </li> --}}

                    <li
                        class="{{ request()->is(request()->slug_instalacion . '/admin/facturas/proveedores') ? 'active' : '' }}">
                        <a
                            href="{{ route('suppliers.index', ['slug_instalacion' => request()->slug_instalacion]) }}">Proveedores</a>
                        <span class="icon-thumbnail">p</span>
                    </li>
                </ul>
            </li>

            <li>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="detailed">
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
