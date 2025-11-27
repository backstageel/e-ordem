<!-- Sidenav Menu Start -->
<div class="sidebar" id="sidebar">

    <!-- Start Logo -->
    <div class="sidebar-logo">
        <div>
            <!-- Logo Normal -->
            <a href="{{ route('admin.dashboard.index') }}" class="logo logo-normal">
                <img src="{{ asset('build/img/logo.png') }}" alt="Ordem dos Médicos de Moçambique">
            </a>

            <!-- Logo Small -->
            <a href="{{ route('admin.dashboard.index') }}" class="logo-small">
                <img src="{{ asset('build/img/logo-small.svg') }}" alt="Ordem dos Médicos de Moçambique">
            </a>

            <!-- Logo Dark -->
            <a href="{{ route('admin.dashboard.index') }}" class="dark-logo">
                <img src="{{ asset('build/img/logo-white.svg') }}" alt="Ordem dos Médicos de Moçambique">
            </a>
        </div>
        <button class="sidenav-toggle-btn btn border-0 p-0 active" id="toggle_btn">
            <i class="ti ti-arrow-left"></i>
        </button>

        <!-- Sidebar Menu Close -->
        <button class="sidebar-close">
            <i class="ti ti-x align-middle"></i>
        </button>
    </div>
    <!-- End Logo -->

    <!-- Sidenav Menu -->
    <div class="sidebar-inner" data-simplebar>
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li>
                    <ul>
                        @php
                            $isGestaoMembrosActive = request()->routeIs('admin.dashboard.index') ||
                                                     request()->routeIs('admin.registrations.*') ||
                                                     request()->routeIs('admin.members.*') ||
                                                     request()->routeIs('admin.cards.*');
                        @endphp
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ $isGestaoMembrosActive ? 'active subdrop' : '' }}">
                                <i class="ti ti-users"></i><span>Gestão de Membros</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('admin.dashboard.index') }}" class="{{ request()->routeIs('admin.dashboard.index') ? 'active' : '' }}"><i class="ti ti-layout-dashboard me-2"></i>Dashboard</a></li>
                                <li><a href="{{ route('admin.registrations.index') }}" class="{{ request()->routeIs('admin.registrations.*') ? 'active' : '' }}"><i class="ti ti-user-plus me-2"></i>Inscrições</a></li>
                                <li><a href="javascript:void(0);" class="text-muted"><i class="ti ti-users me-2"></i>Membros <small class="text-muted">(Inactivo)</small></a></li>
                                <li><a href="javascript:void(0);" class="text-muted"><i class="ti ti-id-badge me-2"></i>Cartões <small class="text-muted">(Inactivo)</small></a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <ul>
                        @php
                            $isProcessosActive = request()->routeIs('admin.documents.*') ||
                                                 request()->routeIs('admin.exams.*') ||
                                                 request()->routeIs('admin.residence.programs.*');
                        @endphp
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ $isProcessosActive ? 'active subdrop' : '' }}">
                                <i class="ti ti-file-text"></i><span>Processos</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="javascript:void(0);" class="text-muted"><i class="ti ti-file-text me-2"></i>Documentos <small class="text-muted">(Inactivo)</small></a></li>
                                <li><a href="javascript:void(0);" class="text-muted"><i class="ti ti-clipboard-list me-2"></i>Exames <small class="text-muted">(Inactivo)</small></a></li>
                                <li><a href="javascript:void(0);" class="text-muted"><i class="ti ti-building-hospital me-2"></i>Residência Médica <small class="text-muted">(Inactivo)</small></a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <ul>
                        @php
                            $isFinanceiroActive = request()->routeIs('admin.payments.*');
                        @endphp
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ $isFinanceiroActive ? 'active subdrop' : '' }}">
                                <i class="ti ti-credit-card"></i><span>Financeiro</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="javascript:void(0);" class="text-muted"><i class="ti ti-credit-card me-2"></i>Pagamentos <small class="text-muted">(Inactivo)</small></a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <ul>
                        @php
                            $isSistemaActive = request()->routeIs('admin.ai.*') ||
                                              request()->routeIs('admin.system.dashboard');
                        @endphp
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ $isSistemaActive ? 'active subdrop' : '' }}">
                                <i class="ti ti-settings"></i><span>Sistema</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="javascript:void(0);" class="text-muted"><i class="ti ti-robot me-2"></i>Inteligência Artificial <small class="text-muted">(Inactivo)</small></a></li>
                                <li><a href="javascript:void(0);" class="text-muted"><i class="ti ti-settings me-2"></i>Administração <small class="text-muted">(Inactivo)</small></a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

</div>
<!-- Sidenav Menu End -->

