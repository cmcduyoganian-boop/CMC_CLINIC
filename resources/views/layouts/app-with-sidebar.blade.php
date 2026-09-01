<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMC School Clinic</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ============ THEME TOKENS ============ */
        body[data-theme="dark"] {
            --bg-page:      linear-gradient(160deg, #060d1c, #080e1a, #060b18);
            --bg-card:      #0b1629;
            --bg-input:     #060f1e;
            --border-card:  #162135;
            --border-input: #1a2a42;
            --border-inner: #111f35;
            --text-heading: #f1f5f9;
            --text-body:    #94a3b8;
            --text-muted:   #64748b;
            --text-label:   #64748b;
            --chart-grid:   #111f35;
            --chart-tick:   #64748b;
            --bg-success:   rgba(39, 174, 96, 0.15);
            --border-success: rgba(39, 174, 96, 0.25);
            --text-success: #4ade80;
            --bg-danger:    rgba(231, 76, 60, 0.15);
            --border-danger: rgba(231, 76, 60, 0.25);
            --text-danger:  #f87171;
            --bg-info:      rgba(56, 189, 248, 0.1);
            --border-info:  rgba(56, 189, 248, 0.2);
            --text-info:    #38bdf8;
            --bg-warning:   rgba(245, 158, 11, 0.15);
            --border-warning: rgba(245, 158, 11, 0.25);
            --text-warning: #fbbf24;
        }

        body[data-theme="light"] {
            --bg-page:      linear-gradient(160deg, #f0f4ff, #f8fafc, #eef2ff);
            --bg-card:      #ffffff;
            --bg-input:     #f1f5f9;
            --border-card:  #e2e8f0;
            --border-input: #cbd5e1;
            --border-inner: #e2e8f0;
            --text-heading: #0f172a;
            --text-body:    #475569;
            --text-muted:   #94a3b8;
            --text-label:   #64748b;
            --chart-grid:   #e2e8f0;
            --chart-tick:   #94a3b8;
            --bg-success:   #e8f7ee;
            --border-success: #b7e4c7;
            --text-success: #157347;
            --bg-danger:    #fef2f2;
            --border-danger: #fecaca;
            --text-danger:  #b91c1c;
            --bg-info:      #e7f3ff;
            --border-info:  #b3d9ff;
            --text-info:    #0066cc;
            --bg-warning:   #fffbeb;
            --border-warning: #fde68a;
            --text-warning: #92400e;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg-page);
            color: var(--text-body);
            min-height: 100vh;
        }

        /* ============ SIDEBAR (slide-in/out) ============ */
        .clinic-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: var(--bg-card);
            border-right: 1px solid var(--border-card);
            z-index: 200;
            display: flex;
            flex-direction: column;
            transform: translateX(-260px);
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .clinic-sidebar.sidebar-open {
            transform: translateX(0);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 199;
        }

        .sidebar-overlay.overlay-visible {
            display: block;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 16px 16px;
            border-bottom: 1px solid var(--border-inner);
            font-size: 16px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .sidebar-logo-img {
            width: 34px;
            height: 34px;
            object-fit: cover;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .sidebar-brand-name {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 768px) {
            .sidebar-brand {
                padding-top: 96px;
            }
        }

        .sidebar-nav {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 12px 10px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-body);
            text-decoration: none;
            transition: all 0.15s;
            border-left: 3px solid transparent;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-link:hover {
            background: var(--bg-input);
            color: var(--text-heading);
        }

        .sidebar-link.active {
            background: rgba(56, 189, 248, 0.12);
            border-left-color: #38bdf8;
            color: #38bdf8;
        }

        .sidebar-icon {
            font-size: 15px;
            width: 18px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 12px 10px;
            border-top: 1px solid var(--border-inner);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-avatar {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: rgba(56, 189, 248, 0.15);
            color: #38bdf8;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
            flex-shrink: 0;
        }

        .sidebar-avatar img,
        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: inherit;
        }

        .sidebar-user-info {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .sidebar-user-name {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-heading);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-role {
            font-size: 10px;
            color: var(--text-muted);
            text-transform: capitalize;
        }

        .sidebar-logout {
            width: 100%;
            padding: 8px;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 10px;
            color: #ef4444;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
        }

        .sidebar-logout:hover {
            background: rgba(239, 68, 68, 0.15);
        }

        .sidebar-toggle-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--bg-input);
            border: 1px solid var(--border-input);
            color: var(--text-heading);
            font-size: 16px;
            cursor: pointer;
            transition: all 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-toggle-btn:hover {
            border-color: #38bdf8;
            color: #38bdf8;
        }

        .sidebar-toggle-btn.toggle-hidden {
            visibility: hidden;
            pointer-events: none;
        }

        /* Push main content on desktop when sidebar is open */
        @media (min-width: 1024px) {
            .app-main {
                margin-left: 0;
                transition: margin-left 0.3s ease;
            }

            .clinic-sidebar.sidebar-open ~ .app-wrapper .app-main {
                margin-left: 260px;
            }

            .clinic-sidebar.sidebar-open ~ .app-wrapper .app-topbar {
                left: 260px;
            }
        }

        /* ============ MAIN LAYOUT ============ */
        .app-wrapper {
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .app-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .app-topbar {
            height: 76px;
            background: var(--bg-card);
            border-bottom: 1px solid var(--border-card);
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 300;
            gap: 20px;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
            flex: 1;
            min-width: 0;
        }

        .topbar-search {
            flex: 1;
            max-width: 400px;
            display: flex;
            align-items: center;
        }

        .search-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .search-input {
            width: 100%;
            padding: 10px 14px 10px 36px;
            border: 1px solid var(--border-input);
            border-radius: 8px;
            font-size: 13px;
            background: var(--bg-input);
            color: var(--text-heading);
            transition: all 0.2s;
            height: 38px;
        }

        .search-input::placeholder {
            color: var(--text-muted);
        }

        .search-input:focus {
            outline: none;
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            color: var(--text-muted);
            font-size: 14px;
            pointer-events: none;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-shrink: 0;
        }

        .topbar-icon-btn {
            background: none;
            border: none;
            color: var(--text-body);
            font-size: 18px;
            cursor: pointer;
            padding: 8px;
            transition: all 0.2s;
            position: relative;
        }

        .topbar-icon-btn:hover {
            color: #38bdf8;
        }

        .theme-toggle-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--bg-input);
            border: 1px solid var(--border-input);
            color: var(--text-heading);
            font-size: 16px;
            cursor: pointer;
            transition: all 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .theme-toggle-btn:hover {
            border-color: #38bdf8;
            color: #38bdf8;
        }

        .notification-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 8px;
            height: 8px;
            background: #e74c3c;
            border-radius: 50%;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .user-profile:hover {
            background: var(--bg-input);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #38bdf8, #2563eb);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            margin: 0;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-heading);
        }

        .user-role {
            margin: 0;
            font-size: 11px;
            color: var(--text-muted);
        }

        .dropdown-arrow {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* CONTENT AREA */
        .app-content {
            flex: 1;
            padding: 100px 24px 24px 24px;
            overflow-y: auto;
        }

        @media (max-width: 768px) {
            .user-info {
                display: none;
            }

            .app-content {
                padding: 92px 16px 16px 16px;
            }

            .topbar-search {
                max-width: 180px;
            }

            .search-input {
                font-size: 12px;
                padding: 8px 10px 8px 30px;
                height: 32px;
            }

            .search-icon {
                left: 10px;
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .topbar-search {
                max-width: 140px;
            }

            .search-input {
                font-size: 11px;
                padding: 6px 8px 6px 26px;
                height: 28px;
            }

            .search-icon {
                left: 8px;
                font-size: 11px;
            }

            .search-input::placeholder {
                font-size: 10px;
            }
        }

        /* SCROLLBAR */
        .clinic-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .clinic-sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .clinic-sidebar::-webkit-scrollbar-thumb {
            background: var(--border-input);
            border-radius: 3px;
        }

        /* ============ TOPBAR PROFILE POPUP ============ */
        .user-profile {
            position: relative;
        }

        .profile-popup {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 320px;
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all 0.2s ease;
            z-index: 1000;
            overflow: hidden;
        }

        .profile-popup.open {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .profile-popup-header {
            display: flex;
            gap: 14px;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid var(--border-inner);
        }

        .popup-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 700;
            overflow: hidden;
            flex-shrink: 0;
        }

        .popup-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .popup-user-info {
            flex: 1;
            min-width: 0;
        }

        .popup-user-info h3 {
            margin: 0 0 4px 0;
            font-size: 15px;
            font-weight: 700;
            color: var(--text-heading);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .popup-user-info p {
            margin: 0 0 4px 0;
            font-size: 12px;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .popup-role {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            background: rgba(52, 152, 219, 0.15);
            color: #3498db;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .profile-popup-body {
            padding: 12px 20px;
            border-bottom: 1px solid var(--border-inner);
        }

        .popup-info-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            font-size: 12px;
            color: var(--text-body);
        }

        .popup-info-row i {
            width: 16px;
            text-align: center;
            color: var(--text-muted);
            font-size: 11px;
        }

        .popup-info-row span {
            color: var(--text-heading);
            font-weight: 600;
        }

        .profile-popup-footer {
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .popup-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: var(--text-body);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.15s;
        }

        .popup-link:hover {
            background: var(--bg-input);
            color: var(--text-heading);
        }

        .popup-link i {
            width: 18px;
            text-align: center;
            color: var(--text-muted);
            font-size: 13px;
        }

        .popup-logout-form {
            display: block;
        }

        .popup-logout {
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
            text-align: left;
        }

        .popup-logout:hover {
            background: rgba(231, 76, 60, 0.1);
            color: #f87171;
        }

        .popup-logout:hover i {
            color: #f87171;
        }
    </style>
</head>
<body data-theme="dark">
    <!-- SIDEBAR OVERLAY -->
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <!-- SIDEBAR -->
    <aside id="clinicSidebar" class="clinic-sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('images/cmc-logo.png') }}" alt="CMC Logo" class="sidebar-logo-img">
            <span class="sidebar-brand-name">CMC Clinic</span>
        </div>

        <nav class="sidebar-nav">
            @if (in_array(auth()->user()->role, ['student', 'faculty', 'staff'], true))
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line sidebar-icon"></i> Dashboard
                </a>
                <a href="{{ route('patient.records') }}" class="sidebar-link {{ request()->routeIs('patient.records') ? 'active' : '' }}">
                    <i class="fas fa-file-medical sidebar-icon"></i> Records
                </a>
                <a href="{{ route('patient.profile') }}" class="sidebar-link {{ request()->routeIs('patient.profile') || request()->routeIs('patient.profile.update') ? 'active' : '' }}">
                    <i class="fas fa-user sidebar-icon"></i> Profile
                </a>
                <a href="{{ route('dashboard') }}#appointments" class="sidebar-link {{ request()->fullUrlIs(route('dashboard') . '#appointments') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt sidebar-icon"></i> Appointments
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line sidebar-icon"></i> Dashboard
                </a>
                @if (auth()->user()->role !== 'clinic_staff')
                    <a href="{{ route('patients.index') }}" class="sidebar-link {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                        <i class="fas fa-users sidebar-icon"></i> Patients
                    </a>
                @endif
                <a href="{{ route('clinic-visit.index') }}" class="sidebar-link {{ request()->routeIs('clinic-visit.*') ? 'active' : '' }}">
                    <i class="fas fa-file-medical sidebar-icon"></i> Clinical Records
                </a>
                <a href="{{ route('medicines.index') }}" class="sidebar-link {{ request()->routeIs('medicines.*') ? 'active' : '' }}">
                    <i class="fas fa-pills sidebar-icon"></i> Inventory
                </a>
                @if (auth()->user()->role !== 'clinic_staff')
                    <a href="{{ route('appointments.index') }}" class="sidebar-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt sidebar-icon"></i> Appointments
                    </a>
                    <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar sidebar-icon"></i> Reports
                    </a>
                    <a href="{{ route('forms.index') }}" class="sidebar-link {{ request()->routeIs('forms.*') ? 'active' : '' }}">
                        <i class="fas fa-file-contract sidebar-icon"></i> Forms
                    </a>
                @endif
                @if (auth()->user() && auth()->user()->role === 'clinic_nurse')
                    <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield sidebar-icon"></i> User Management
                    </a>
                    <a href="{{ route('clinic-staff.index') }}" class="sidebar-link {{ request()->routeIs('clinic-staff.*') ? 'active' : '' }}">
                        <i class="fas fa-user-md sidebar-icon"></i> Clinic Staff
                    </a>
                @endif
            @endif
            <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="fas fa-sliders-h sidebar-icon"></i> Settings
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-avatar">
                    @if (auth()->user()->getAvatarUrl())
                        <img src="{{ auth()->user()->getAvatarUrl() }}" alt="{{ auth()->user()->name }}">
                    @else
                        {{ substr(auth()->user()->name, 0, 2) }}
                    @endif
                </div>
                <div class="sidebar-user-info">
                    <span class="sidebar-user-name">{{ auth()->user()->name }}</span>
                    <span class="sidebar-user-role">{{ str_replace('_', ' ', auth()->user()->role) }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="app-wrapper">
        <!-- MAIN CONTENT -->
        <main class="app-main">
            <div class="app-topbar">
                <div class="topbar-left">
                    <button type="button" class="sidebar-toggle-btn" id="sidebarToggle" title="Toggle menu">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="topbar-search">
                        <div class="search-input-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="globalSearchInput" class="search-input" placeholder="Search patients, records...">
                        </div>
                    </div>
                </div>

                <div class="topbar-right">
                    <button type="button" class="theme-toggle-btn" id="themeToggle" title="Toggle theme">
                        <i class="fas fa-sun"></i>
                    </button>
                    <button type="button" class="topbar-icon-btn" title="Notifications">
                        <i class="fas fa-bell"></i>
                        <div class="notification-badge"></div>
                    </button>
                    <div class="user-profile" id="topbarUserProfile">
                        <div class="user-avatar">
                            @if (auth()->user()->getAvatarUrl())
                                <img src="{{ auth()->user()->getAvatarUrl() }}" alt="{{ auth()->user()->name }}">
                            @else
                                {{ substr(auth()->user()->name, 0, 1) }}
                            @endif
                        </div>
                        <div class="user-info">
                            <p class="user-name">{{ auth()->user()->name }}</p>
                            <p class="user-role">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
                        </div>
                        <span class="dropdown-arrow"><i class="fas fa-chevron-down"></i></span>

                        <div class="profile-popup" id="profilePopup">
                            <div class="profile-popup-header">
                                <div class="popup-avatar">
                                    @if (auth()->user()->getAvatarUrl())
                                        <img src="{{ auth()->user()->getAvatarUrl() }}" alt="{{ auth()->user()->name }}">
                                    @else
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    @endif
                                </div>
                                <div class="popup-user-info">
                                    <h3>{{ auth()->user()->name }}</h3>
                                    <p>{{ auth()->user()->email }}</p>
                                    <span class="popup-role">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</span>
                                </div>
                            </div>
                            <div class="profile-popup-body">
                                @if (auth()->user()->phone)
                                    <div class="popup-info-row">
                                        <i class="fas fa-phone"></i>
                                        <span>{{ auth()->user()->phone }}</span>
                                    </div>
                                @endif
                                @if (auth()->user()->approval_status)
                                    <div class="popup-info-row">
                                        <i class="fas fa-circle"></i>
                                        <span>Status: {{ ucfirst(auth()->user()->approval_status) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="profile-popup-footer">
                                <a href="{{ route('settings.index') }}" class="popup-link">
                                    <i class="fas fa-user-cog"></i> Account Settings
                                </a>
                                <a href="{{ route('patient.profile') }}" class="popup-link">
                                    <i class="fas fa-user"></i> My Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="popup-logout-form">
                                    @csrf
                                    <button type="submit" class="popup-link popup-logout">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PAGE CONTENT -->
            <div class="app-content">
                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot }}
                @endif
            </div>
        </main>
    </div>

    <script>
        // ============ SIDEBAR TOGGLE (closed by default, hamburger hides while open) ============
        (function () {
            const sidebar = document.getElementById('clinicSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtn = document.getElementById('sidebarToggle');
            const isDesktop = () => window.innerWidth >= 1024;

            function openSidebar() {
                sidebar.classList.add('sidebar-open');
                overlay.classList.add('overlay-visible');
                toggleBtn.classList.add('toggle-hidden');
                localStorage.setItem('clinicSidebar', 'open');
            }

            function closeSidebar() {
                sidebar.classList.remove('sidebar-open');
                overlay.classList.remove('overlay-visible');
                toggleBtn.classList.remove('toggle-hidden');
                localStorage.setItem('clinicSidebar', 'closed');
            }

            toggleBtn.addEventListener('click', function () {
                sidebar.classList.contains('sidebar-open') ? closeSidebar() : openSidebar();
            });

            overlay.addEventListener('click', closeSidebar);

            // Closed by default on every page load, regardless of device or previous state.
            closeSidebar();

            window.addEventListener('resize', function () {
                if (isDesktop()) {
                    overlay.classList.remove('overlay-visible');
                }
            });
        })();

        // ============ THEME TOGGLE ============
        (function () {
            const body = document.body;
            const btn = document.getElementById('themeToggle');
            const saved = localStorage.getItem('clinicTheme') || 'dark';
            body.setAttribute('data-theme', saved);
            btn.innerHTML = saved === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';

            btn.addEventListener('click', function () {
                const next = body.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                body.setAttribute('data-theme', next);
                localStorage.setItem('clinicTheme', next);
                btn.innerHTML = next === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
                window.dispatchEvent(new CustomEvent('clinic-theme-changed', { detail: { theme: next } }));
            });
        })();

        // GLOBAL TOPBAR SEARCH — dispatches to whichever Livewire component
        // on the current page is listening for it (e.g. the Patients list).
        (function () {
            const globalSearchInput = document.getElementById('globalSearchInput');
            if (!globalSearchInput) return;

            let debounceTimer;
            globalSearchInput.addEventListener('input', function (e) {
                clearTimeout(debounceTimer);
                const term = e.target.value;
                debounceTimer = setTimeout(function () {
                    if (typeof Livewire !== 'undefined') {
                        Livewire.dispatch('global-search', { term: term });
                    }
                }, 300);
            });
        })();

        // ============ TOPBAR PROFILE POPUP ============
        (function () {
            const profileTrigger = document.getElementById('topbarUserProfile');
            const popup = document.getElementById('profilePopup');
            if (!profileTrigger || !popup) return;

            profileTrigger.addEventListener('click', function (e) {
                e.stopPropagation();
                popup.classList.toggle('open');
            });

            document.addEventListener('click', function (e) {
                if (!profileTrigger.contains(e.target)) {
                    popup.classList.remove('open');
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    popup.classList.remove('open');
                }
            });
        })();
    </script>
</body>
</html>