<!-- Sidenav Menu Start -->
<div class="sidebar" id="sidebar">

    <!-- Start Logo -->
    <div class="sidebar-logo">
        <div>
            <!-- Logo Normal -->
            <a href="{{ route('member.dashboard.index') }}" class="logo logo-normal">
                <img src="{{ asset('build/img/logo.png') }}" alt="Ordem dos Médicos de Moçambique">
            </a>

            <!-- Logo Small -->
            <a href="{{ route('member.dashboard.index') }}" class="logo-small">
                <img src="{{ asset('build/img/logo-small.svg') }}" alt="Ordem dos Médicos de Moçambique">
            </a>

            <!-- Logo Dark -->
            <a href="{{ route('member.dashboard.index') }}" class="dark-logo">
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
                            $isMinhaContaActive = request()->routeIs('member.dashboard.index') ||
                                                  request()->routeIs('member.profile') ||
                                                  request()->routeIs('member.card.index');
                        @endphp
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ $isMinhaContaActive ? 'active subdrop' : '' }}">
                                <i class="ti ti-user"></i><span>Minha Conta</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('member.dashboard.index') }}" class="{{ request()->routeIs('member.dashboard.index') ? 'active' : '' }}"><i class="ti ti-layout-dashboard me-2"></i>Dashboard</a></li>
                                <li><a href="{{ route('member.profile') }}" class="{{ request()->routeIs('member.profile') ? 'active' : '' }}"><i class="ti ti-user me-2"></i>Meu Perfil</a></li>
                                <li><a href="{{ route('member.card.index') }}" class="{{ request()->routeIs('member.card.index') ? 'active' : '' }}"><i class="ti ti-id-badge me-2"></i>Cartão Digital</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <ul>
                        @php
                            $isProcessosActive = request()->routeIs('member.registrations.*') ||
                                                request()->routeIs('member.documents.*') ||
                                                request()->routeIs('member.exams.*');
                        @endphp
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ $isProcessosActive ? 'active subdrop' : '' }}">
                                <i class="ti ti-file-text"></i><span>Processos</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('member.registrations.index') }}" class="{{ request()->routeIs('member.registrations.*') ? 'active' : '' }}"><i class="ti ti-user-plus me-2"></i>Minhas Inscrições</a></li>
                                <li><a href="{{ route('member.documents.index') }}" class="{{ request()->routeIs('member.documents.*') ? 'active' : '' }}"><i class="ti ti-file-text me-2"></i>Meus Documentos</a></li>
                                <li><a href="{{ route('member.exams.index') }}" class="{{ request()->routeIs('member.exams.*') ? 'active' : '' }}"><i class="ti ti-clipboard-list me-2"></i>Meus Exames</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <ul>
                        @php
                            $isOutrosActive = request()->routeIs('member.payments.*') ||
                                             request()->routeIs('member.notifications.*') ||
                                             request()->routeIs('member.support');
                        @endphp
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ $isOutrosActive ? 'active subdrop' : '' }}">
                                <i class="ti ti-dots"></i><span>Outros</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('member.payments.index') }}" class="{{ request()->routeIs('member.payments.*') ? 'active' : '' }}"><i class="ti ti-credit-card me-2"></i>Pagamentos</a></li>
                                <li><a href="{{ route('member.notifications.index') }}" class="{{ request()->routeIs('member.notifications.*') ? 'active' : '' }}"><i class="ti ti-bell me-2"></i>Notificações</a></li>
                                <li><a href="{{ route('member.support') }}" class="{{ request()->routeIs('member.support') ? 'active' : '' }}"><i class="ti ti-help-circle me-2"></i>Suporte</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

</div>
<!-- Sidenav Menu End -->

