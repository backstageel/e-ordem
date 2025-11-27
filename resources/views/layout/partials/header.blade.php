<!-- Topbar Start -->
<header class="navbar-header">
    <div class="page-container topbar-menu">
        <div class="d-flex align-items-center gap-2">

            <!-- Logo -->
            <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard.index') : (auth()->user()->hasRole('member') ? route('member.dashboard.index') : route('dashboard')) }}" class="logo">
                <img src="{{URL::asset('build/img/logo.png')}}" alt="logo" class="logo-lg">
            </a>

            <!-- Sidebar Mobile Button -->
            <a id="mobile_btn" class="mobile-btn" href="#sidebar">
                <i class="ti ti-menu-deep fs-24"></i>
            </a>

            <button class="sidenav-toggle-btn btn border-0 p-0 active" id="toggle_btn2">
                <i class="ti ti-arrow-right"></i>
            </button>

        </div>

        @if (!Route::is(['doctor-dashboard', 'doctors-appointments', 'doctors-appointment-details', 'doctors-patient-details', 'online-consultations', 'doctors-schedules', 'doctors-prescriptions', 'doctors-prescription-details', 'doctors-leaves', 'doctors-reviews', 'doctors-profile-settings', 'doctors-password-settings', 'doctors-notification-settings', 'doctors-notifications', 'patient-dashboard', 'patient-appointments', 'patient-appointment-details', 'patients-doctor-details', 'patient-doctors', 'patient-prescriptions', 'patient-prescription-details', 'patient-invoices', 'patient-invoice-details', 'patient-profile-settings', 'patient-password-settings', 'patient-notifications-settings', 'patient-notifications']))
        <div class="d-flex align-items-center">

            @if (!Route::is(['layout-dark', 'layout-mini', 'layout-hidden', 'layout-hover-view', 'layout-full-width', 'layout-rtl']))
            <!-- Light/Dark Mode Button -->
            <div class="header-item d-none d-sm-flex me-2">
                <button class="topbar-link btn btn-icon topbar-link" id="light-dark-mode" type="button">
                    <i class="ti ti-moon fs-16"></i>
                </button>
            </div>
            @endif

            <!-- Notification Dropdown -->
            <div class="header-item">
                <div class="dropdown me-3">
                    @php
                        $notifications = auth()->user()->unreadNotifications()->latest()->take(5)->get();
                        $unreadCount = auth()->user()->unreadNotifications()->count();
                    @endphp

                    <button class="topbar-link btn btn-icon topbar-link dropdown-toggle drop-arrow-none position-relative" data-bs-toggle="dropdown" data-bs-offset="0,24" type="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti ti-bell-check fs-16 animate-ring"></i>
                        @if($unreadCount > 0)
                            <span class="notification-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                        @endif
                    </button>

                    <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg" style="min-height: 300px;">

                        <div class="p-2 border-bottom">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 fs-16 fw-semibold">Notificações</h6>
                                </div>
                            </div>
                        </div>

                        <!-- Notification Body -->
                        <div class="notification-body position-relative z-2 rounded-0" data-simplebar>
                            @forelse($notifications as $notification)
                                <div class="dropdown-item notification-item py-3 text-wrap border-bottom" id="notification-{{ $notification->id }}">
                                    <div class="d-flex">
                                        <div class="me-2 position-relative flex-shrink-0">
                                            <div class="avatar-md rounded-circle bg-primary d-flex align-items-center justify-content-center">
                                                <i class="ti ti-bell text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-0 fw-medium text-dark">{{ $notification->data['title'] ?? 'Notificação' }}</p>
                                            <p class="mb-1 text-wrap">
                                                {{ $notification->data['message'] ?? $notification->data['body'] ?? 'Nova notificação' }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fs-12"><i class="ti ti-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}</span>
                                                <div class="notification-action d-flex align-items-center float-end gap-2">
                                                    <form method="POST" action="{{ route('notifications.mark-read', $notification->id) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="notification-read rounded-circle bg-danger border-0" data-bs-toggle="tooltip" title="Marcar como lida" aria-label="Marcar como lida"></button>
                                                    </form>
                                                    <button class="btn rounded-circle p-0" data-dismissible="#notification-{{ $notification->id }}">
                                                        <i class="ti ti-x"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="dropdown-item text-center py-5">
                                    <i class="ti ti-bell-off fs-48 text-muted mb-2"></i>
                                    <p class="text-muted mb-0">Nenhuma notificação</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- View All-->
                        @if($unreadCount > 0)
                        <div class="p-2 rounded-bottom border-top text-center">
                            <a href="{{ auth()->user()->hasRole('admin') ? route('admin.notifications.index') : route('member.notifications.index') }}" class="text-center text-decoration-underline fs-14 mb-0">
                                Ver Todas as Notificações
                            </a>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="dropdown profile-dropdown d-flex align-items-center justify-content-center">
                @php
                    $userPhoto = auth()->user()->person?->profile_picture_url
                        ? asset('storage/' . auth()->user()->person->profile_picture_url)
                        : URL::asset('build/img/users/user-01.jpg');
                @endphp
                <a href="javascript:void(0);" class="topbar-link dropdown-toggle drop-arrow-none position-relative d-flex align-items-center gap-2" data-bs-toggle="dropdown" data-bs-offset="0,22" aria-haspopup="false" aria-expanded="false">
                    <img src="{{ $userPhoto }}" width="32" class="rounded-circle d-flex" alt="user-image">
                    <span class="online text-success"><i class="ti ti-circle-filled d-flex bg-white rounded-circle border border-1 border-white"></i></span>
                    <!-- User Name and Role -->
                    <div class="d-flex flex-column ms-2 d-none d-md-flex">
                        <span class="fw-semibold text-dark fs-14">{{ auth()->user()->name }}</span>
                        <span class="text-muted fs-12">{{ auth()->user()->roles->first()?->name ?? 'Usuário' }}</span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-2">

                    <div class="d-flex align-items-center bg-light rounded-3 p-2 mb-2">
                        <img src="{{ $userPhoto }}" class="rounded-circle" width="42" height="42" alt="">
                        <div class="ms-2">
                            <p class="fw-medium text-dark mb-0">{{ auth()->user()->name }}</p>
                            <span class="d-block fs-13">{{ auth()->user()->roles->first()?->name ?? 'Usuário' }}</span>
                        </div>
                    </div>

                    <!-- Item-->
                    @php
                        if (auth()->user()->hasRole('member')) {
                            $profileRoute = route('member.profile');
                        } elseif (auth()->user()->hasRole(['admin', 'super-admin'])) {
                            $profileRoute = url('/profile');
                        } else {
                            $profileRoute = url('/profile');
                        }
                    @endphp
                    <a href="{{ $profileRoute }}" class="dropdown-item">
                        <i class="ti ti-user-circle me-1 align-middle"></i>
                        <span class="align-middle">Meu Perfil</span>
                    </a>

                    <!-- Separator -->
                    <div class="pt-2 mt-2 border-top">
                        <!-- Item-->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger w-100 text-start border-0 bg-transparent">
                                <i class="ti ti-logout me-1 fs-17 align-middle"></i>
                                <span class="align-middle">Sair</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        @endif

    </div>
</header>
<!-- Topbar End -->
