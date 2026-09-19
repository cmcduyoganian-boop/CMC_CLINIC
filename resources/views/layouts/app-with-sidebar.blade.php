<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>CMC School Clinic</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        /* ============ THEME TOKENS ============ */
        body[data-theme="dark"] {
            --bg-page:        linear-gradient(160deg,#060d1c,#080e1a,#060b18);
            --bg-card:        #0b1629;
            --bg-input:       #060f1e;
            --border-card:    #162135;
            --border-input:   #1a2a42;
            --border-inner:   #111f35;
            --text-heading:   #f1f5f9;
            --text-body:      #94a3b8;
            --text-muted:     #64748b;
            --text-label:     #64748b;
            --chart-grid:     #111f35;
            --chart-tick:     #64748b;
            --bg-success:     rgba(39,174,96,0.15);
            --border-success: rgba(39,174,96,0.25);
            --text-success:   #4ade80;
            --bg-danger:      rgba(231,76,60,0.15);
            --border-danger:  rgba(231,76,60,0.25);
            --text-danger:    #f87171;
            --bg-info:        rgba(56,189,248,0.1);
            --border-info:    rgba(56,189,248,0.2);
            --text-info:      #38bdf8;
            --bg-warning:     rgba(245,158,11,0.15);
            --border-warning: rgba(245,158,11,0.25);
            --text-warning:   #fbbf24;
            --sidebar-bg:     rgba(6,15,30,0.88);
            --sidebar-border: rgba(255,255,255,0.06);
            --topbar-bg:      rgba(6,15,30,0.82);
            --topbar-border:  rgba(255,255,255,0.06);
        }

        body[data-theme="light"] {
            --bg-page:        linear-gradient(160deg,#f0f4ff,#f8fafc,#eef2ff);
            --bg-card:        #ffffff;
            --bg-input:       #f1f5f9;
            --border-card:    #e2e8f0;
            --border-input:   #cbd5e1;
            --border-inner:   #e2e8f0;
            --text-heading:   #0f172a;
            --text-body:      #475569;
            --text-muted:     #94a3b8;
            --text-label:     #64748b;
            --chart-grid:     #e2e8f0;
            --chart-tick:     #94a3b8;
            --bg-success:     #e8f7ee;
            --border-success: #b7e4c7;
            --text-success:   #157347;
            --bg-danger:      #fef2f2;
            --border-danger:  #fecaca;
            --text-danger:    #b91c1c;
            --bg-info:        #e7f3ff;
            --border-info:    #b3d9ff;
            --text-info:      #0066cc;
            --bg-warning:     #fffbeb;
            --border-warning: #fde68a;
            --text-warning:   #92400e;
            --sidebar-bg:     rgba(255,255,255,0.80);
            --sidebar-border: rgba(0,0,0,0.07);
            --topbar-bg:      rgba(255,255,255,0.82);
            --topbar-border:  rgba(0,0,0,0.07);
        }

        body {
            font-family: 'Figtree','Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
            background: var(--bg-page);
            color: var(--text-body);
            min-height: 100vh;
            max-width: 100vw;
            overflow-x: hidden;
            padding: env(safe-area-inset-top) env(safe-area-inset-right) env(safe-area-inset-bottom) env(safe-area-inset-left);
        }

        /* ── Date Filter Bar (shared across report pages) ─── */
        .date-filter-bar {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 10px;
            padding: 14px 18px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .date-filter-form { display:flex; align-items:center; flex-wrap:wrap; gap:14px; }
        .dff-presets { display:flex; gap:6px; flex-wrap:wrap; }
        .dff-preset {
            display:inline-flex; align-items:center; padding:6px 14px;
            border-radius:20px; font-size:12px; font-weight:700; text-decoration:none;
            background:var(--bg-input); color:var(--text-muted);
            border:1px solid var(--border-card); transition:all 0.18s; white-space:nowrap;
        }
        .dff-preset:hover { border-color:#38bdf8; color:#38bdf8; }
        .dff-preset.active {
            background:linear-gradient(135deg,rgba(56,189,248,0.2),rgba(37,99,235,0.15));
            border-color:#38bdf8; color:#38bdf8;
        }
        .dff-range { display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-left:auto; }
        .dff-label { font-size:12px; color:var(--text-muted); font-weight:600; display:flex; align-items:center; gap:5px; white-space:nowrap; }
        .dff-input {
            background:var(--bg-input); border:1px solid var(--border-input);
            border-radius:6px; padding:7px 10px; font-size:12px;
            color:var(--text-heading); font-family:inherit; transition:border-color 0.2s; cursor:pointer;
        }
        .dff-input:focus { outline:none; border-color:#38bdf8; box-shadow:0 0 0 3px rgba(56,189,248,0.1); }
        .dff-sep { font-size:12px; color:var(--text-muted); }
        .dff-apply {
            background:linear-gradient(135deg,#38bdf8,#2563eb); color:white;
            border:none; border-radius:6px; padding:7px 14px; font-size:12px; font-weight:700;
            cursor:pointer; display:flex; align-items:center; gap:6px; font-family:inherit; transition:opacity 0.2s;
        }
        .dff-apply:hover { opacity:0.85; }
        .dff-clear {
            background:rgba(231,76,60,0.12); color:#e74c3c;
            border:1px solid rgba(231,76,60,0.3); border-radius:6px;
            padding:6px 12px; font-size:12px; font-weight:700; text-decoration:none;
            display:flex; align-items:center; gap:5px; transition:background 0.2s;
        }
        .dff-clear:hover { background:rgba(231,76,60,0.22); }
        .dff-result-info {
            font-size:12px; color:#38bdf8; background:rgba(56,189,248,0.07);
            border:1px solid rgba(56,189,248,0.2); border-radius:6px; padding:7px 14px;
            display:flex; align-items:center; gap:8px;
        }
        .dff-result-info strong { color:var(--text-heading); }
        @media(max-width:768px){
            .dff-range { margin-left:0; }
            .date-filter-form { flex-direction:column; align-items:stretch; }
        }

        /* ============================================================
           GLASSMORPHISM SIDEBAR
           Desktop (>=1024px) : always visible — no JS needed
           Mobile  (<1024px)  : slide-in/out with overlay
        ============================================================ */
        .clinic-sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: 256px;
            background: var(--sidebar-bg);
            backdrop-filter: blur(24px) saturate(1.8);
            -webkit-backdrop-filter: blur(24px) saturate(1.8);
            border-right: 1px solid var(--sidebar-border);
            z-index: 200;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            transition: transform 0.28s cubic-bezier(.4,0,.2,1);
            transform: translateX(0); /* desktop: always visible */
        }

        @media(max-width:1023px) {
            .clinic-sidebar {
                transform: translateX(-256px);
                padding-bottom: calc(18px + env(safe-area-inset-bottom));
            }
            .clinic-sidebar.sidebar-open { transform: translateX(0); }
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 199;
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.overlay-visible { display: block; }
        @media(min-width:1024px) { .sidebar-overlay { display: none !important; } }

        /* Brand */
        .sidebar-brand {
            display: flex; align-items: center; gap: 10px;
            padding: 20px 16px 16px;
            border-bottom: 1px solid var(--sidebar-border);
            font-size: 15px; font-weight: 800; color: var(--text-heading);
            letter-spacing: -0.2px; flex-shrink: 0;
        }
        .sidebar-logo-img {
            width: 38px;
            height: 38px;
            object-fit: cover;
            border-radius: 50%;
            flex-shrink: 0;
            background: rgba(255,255,255,0.08);
            box-shadow: 0 4px 12px rgba(56,189,248,0.18);
            border: 2px solid rgba(255,255,255,0.4);
        }
        .sidebar-brand-name { white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

        /* Nav */
        .sidebar-nav {
            flex: 1; display: flex; flex-direction: column; gap: 2px; padding: 14px 10px;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 10px;
            font-size: 13px; font-weight: 600; color: var(--text-body);
            text-decoration: none; transition: all 0.16s;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            position: relative;
        }
        .sidebar-link:hover { background: rgba(56,189,248,0.07); color: var(--text-heading); }
        .sidebar-link.active {
            background: linear-gradient(135deg,rgba(56,189,248,0.15),rgba(37,99,235,0.09));
            color: #38bdf8;
            box-shadow: 0 0 0 1px rgba(56,189,248,0.18) inset, 0 4px 14px rgba(56,189,248,0.07);
        }
        .sidebar-link.active::before {
            content: ''; position: absolute;
            left: 0; top: 22%; bottom: 22%;
            width: 3px;
            background: linear-gradient(180deg,#38bdf8,#2563eb);
            border-radius: 0 3px 3px 0;
        }
        .sidebar-icon { font-size: 14px; width: 18px; text-align: center; flex-shrink: 0; }

        /* Footer */
        .sidebar-footer {
            padding: 12px 10px; border-top: 1px solid var(--sidebar-border);
            display: flex; flex-direction: column; gap: 10px; flex-shrink: 0;
        }
        .sidebar-user { display: flex; align-items: center; gap: 10px; padding: 4px 2px; }
        .sidebar-avatar {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg,rgba(56,189,248,0.2),rgba(37,99,235,0.15));
            color: #38bdf8; font-size: 12px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            text-transform: uppercase; flex-shrink: 0;
            border: 1px solid rgba(56,189,248,0.2);
        }
        .sidebar-avatar img, .user-avatar img {
            width:100%; height:100%; object-fit:cover; border-radius:inherit;
        }
        .sidebar-user-info { display:flex; flex-direction:column; min-width:0; }
        .sidebar-user-name { font-size:12px; font-weight:700; color:var(--text-heading); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .sidebar-user-role { font-size:10px; color:var(--text-muted); text-transform:capitalize; }
        .sidebar-logout {
            width:100%; padding:9px;
            background:rgba(239,68,68,0.07); border:1px solid rgba(239,68,68,0.18);
            border-radius:10px; color:#ef4444; font-size:12px; font-weight:700;
            cursor:pointer; transition:all 0.15s; font-family:inherit;
            display:flex; align-items:center; justify-content:center; gap:8px;
        }
        .sidebar-logout:hover { background:rgba(239,68,68,0.15); }

        .clinic-sidebar::-webkit-scrollbar { width:4px; }
        .clinic-sidebar::-webkit-scrollbar-track { background:transparent; }
        .clinic-sidebar::-webkit-scrollbar-thumb { background:var(--border-input); border-radius:2px; }

        /* ============================================================
           TOPBAR — glass, no page title
        ============================================================ */
        .app-topbar {
            height: 68px;
            background: var(--topbar-bg);
            backdrop-filter: blur(20px) saturate(1.5);
            -webkit-backdrop-filter: blur(20px) saturate(1.5);
            border-bottom: 1px solid var(--topbar-border);
            padding: 0 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            position: fixed;
            top: 0; right: 0;
            left: 256px; /* always offset on desktop */
            z-index: 190;
            transition: left 0.28s cubic-bezier(.4,0,.2,1);
        }
        @media(max-width:1023px) {
            .app-topbar {
                left:0;
                padding:0 16px;
                gap:10px;
                padding-left: calc(16px + env(safe-area-inset-left));
                padding-right: calc(16px + env(safe-area-inset-right));
            }
        }

        /* Hamburger — mobile only */
        .sidebar-toggle-btn {
            display: none;
            width: 38px; height: 38px; border-radius: 10px;
            background: rgba(56,189,248,0.07); border: 1px solid rgba(56,189,248,0.15);
            color: var(--text-heading); font-size: 16px;
            cursor: pointer; transition: all 0.15s;
            align-items: center; justify-content: center; flex-shrink: 0;
        }
        @media(max-width:1023px) { .sidebar-toggle-btn { display:flex; } }
        .sidebar-toggle-btn:hover {
            border-color:#38bdf8; color:#38bdf8; background:rgba(56,189,248,0.12);
        }

        /* Topbar search pill */
        .topbar-search {
            flex: 1; max-width: 360px;
            display: flex; align-items: center; gap: 8px;
            height: 40px; padding: 0 14px;
            background: rgba(56,189,248,0.04);
            border: 1px solid rgba(56,189,248,0.12);
            border-radius: 20px; transition: all 0.2s;
        }
        .topbar-search:focus-within {
            border-color: rgba(56,189,248,0.4);
            background: rgba(56,189,248,0.08);
            box-shadow: 0 0 0 3px rgba(56,189,248,0.06);
        }
        .topbar-search-icon { color:var(--text-muted); font-size:13px; flex-shrink:0; }
        .topbar-search-input {
            flex:1; border:none; outline:none; background:transparent;
            color:var(--text-heading); font-size:13px; font-family:inherit;
        }
        .topbar-search-input::placeholder { color:var(--text-muted); }
        @media(max-width:640px) { .topbar-search { display:none; } }

        /* Right section */
        .topbar-right {
            display: flex; align-items: center; gap: 8px; margin-left: auto; flex-shrink: 0;
            min-width: 0;
        }
        .topbar-icon-btn {
            background:none; border:none; color:var(--text-body); font-size:17px;
            cursor:pointer; padding:8px; transition:all 0.2s; position:relative;
        }
        .topbar-icon-btn:hover { color:#38bdf8; }
        .theme-toggle-btn {
            width:38px; height:38px; border-radius:10px;
            background:rgba(56,189,248,0.06); border:1px solid rgba(56,189,248,0.14);
            color:var(--text-heading); font-size:15px; cursor:pointer;
            transition:all 0.15s; display:flex; align-items:center; justify-content:center;
        }
        .theme-toggle-btn:hover { border-color:#38bdf8; color:#38bdf8; background:rgba(56,189,248,0.12); }
        .notification-badge {
            position:absolute; top:2px; right:1px; min-width:18px; height:18px;
            padding:0 5px; background:#e74c3c; border-radius:999px;
            color:white; font-size:10px; font-weight:700; display:flex;
            align-items:center; justify-content:center; border:2px solid var(--topbar-bg);
        }
        .notification-wrapper {
            position: relative;
        }
        .notification-dropdown {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            width: 320px;
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 12px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
            display: none;
            z-index: 220;
            overflow: hidden;
        }
        .notification-dropdown.open {
            display: block;
        }
        .notification-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            border-bottom: 1px solid var(--border-card);
            background: rgba(56, 189, 248, 0.04);
            color: var(--text-heading);
            font-size: 12px;
            font-weight: 700;
        }
        .notification-header .count-pill {
            background: rgba(231, 76, 60, 0.12);
            color: var(--text-danger);
            border-radius: 999px;
            padding: 4px 8px;
            font-size: 10px;
        }
        .notification-list {
            display: flex;
            flex-direction: column;
            max-height: 360px;
            overflow-y: auto;
        }
        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 14px;
            border-bottom: 1px solid var(--border-card);
            text-decoration: none;
            color: var(--text-body);
            transition: background 0.15s ease;
        }
        .notification-item:hover {
            background: rgba(56, 189, 248, 0.04);
        }
        .notification-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(56, 189, 248, 0.08);
            color: #38bdf8;
            flex-shrink: 0;
        }
        .notification-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
            min-width: 0;
        }
        .notification-text strong {
            color: var(--text-heading);
            font-size: 12px;
            line-height: 1.35;
        }
        .notification-text small {
            color: var(--text-muted);
            font-size: 11px;
        }
        .notification-empty {
            padding: 18px 14px;
            text-align: center;
            color: var(--text-muted);
            font-size: 12px;
        }
        .user-profile {
            display:flex; align-items:center; gap:10px; cursor:pointer;
            padding:6px 10px; border-radius:10px; transition:all 0.2s; position:relative;
        }
        .user-profile:hover { background:rgba(56,189,248,0.06); }
        .user-avatar {
            width:36px; height:36px;
            background:linear-gradient(135deg,#38bdf8,#2563eb);
            border-radius:50%; display:flex; align-items:center; justify-content:center;
            color:white; font-weight:700; font-size:14px; flex-shrink:0;
        }
        .user-info { display:flex; flex-direction:column; }
        .user-name { margin:0; font-size:12px; font-weight:700; color:var(--text-heading); }
        .user-role { margin:0; font-size:11px; color:var(--text-muted); }
        .dropdown-arrow { font-size:11px; color:var(--text-muted); }
        @media(max-width:768px) {
            .user-info { display:none; }
            .dropdown-arrow { display:none; }
            .notification-dropdown {
                width: min(300px, calc(100vw - 24px));
                right: -8px;
            }
        }
        @media(max-width:420px) {
            .app-topbar {
                height: 60px;
                gap: 8px;
            }
            .topbar-right {
                gap: 4px;
            }
            .theme-toggle-btn,
            .topbar-icon-btn {
                width: 32px;
                height: 32px;
                padding: 6px;
                font-size: 14px;
            }
            .notification-badge {
                min-width: 16px;
                height: 16px;
                font-size: 9px;
                right: 0;
                top: 0;
            }
            .user-profile {
                padding: 4px 6px;
            }
            .notification-dropdown {
                width: min(260px, calc(100vw - 18px));
                right: -10px;
            }
        }

        /* ============================================================
           MAIN LAYOUT
        ============================================================ */
        .app-wrapper {
            margin-left: 256px; /* permanent on desktop */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.28s cubic-bezier(.4,0,.2,1);
        }
        @media(max-width:1023px) {
            .app-wrapper { margin-left:0; }
            .app-content {
                padding-left: calc(16px + env(safe-area-inset-left));
                padding-right: calc(16px + env(safe-area-inset-right));
            }
        }

        .app-main { flex:1; display:flex; flex-direction:column; min-width:0; }

        .app-content { flex:1; padding:90px 24px 28px 24px; }
        @media(max-width:768px) { .app-content { padding:82px 16px 20px 16px; } }

        /* ============================================================
           PROFILE POPUP
        ============================================================ */
        .profile-popup {
            position:absolute; top:calc(100% + 8px); right:0;
            width:320px; background:var(--bg-card);
            border:1px solid var(--border-card); border-radius:14px;
            box-shadow:0 12px 48px rgba(0,0,0,0.18);
            opacity:0; visibility:hidden; transform:translateY(-8px);
            transition:all 0.2s ease; z-index:1000; overflow:hidden;
        }
        .profile-popup.open { opacity:1; visibility:visible; transform:translateY(0); }
        .profile-popup-header {
            display:flex; gap:14px; align-items:center;
            padding:20px; border-bottom:1px solid var(--border-inner);
        }
        .popup-avatar {
            width:60px; height:60px; border-radius:50%;
            background:linear-gradient(135deg,#2980b9,#1a6ea8);
            color:white; display:flex; align-items:center; justify-content:center;
            font-size:22px; font-weight:700; overflow:hidden; flex-shrink:0;
        }
        .popup-avatar img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
        .popup-user-info { flex:1; min-width:0; }
        .popup-user-info h3 {
            margin:0 0 4px; font-size:15px; font-weight:700; color:var(--text-heading);
            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
        }
        .popup-user-info p {
            margin:0 0 4px; font-size:12px; color:var(--text-muted);
            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
        }
        .popup-role {
            display:inline-block; padding:2px 10px; border-radius:20px;
            font-size:10px; font-weight:700;
            background:rgba(41,128,185,0.12); color:#2980b9;
            text-transform:uppercase; letter-spacing:0.5px;
        }
        .profile-popup-body { padding:12px 20px; border-bottom:1px solid var(--border-inner); }
        .popup-info-row {
            display:flex; align-items:center; gap:10px;
            padding:8px 0; font-size:12px; color:var(--text-body);
        }
        .popup-info-row i { width:16px; text-align:center; color:var(--text-muted); font-size:11px; }
        .popup-info-row span { color:var(--text-heading); font-weight:600; }
        .profile-popup-footer { padding:10px 12px; display:flex; flex-direction:column; gap:2px; }
        .popup-link {
            display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:8px;
            color:var(--text-body); text-decoration:none; font-size:13px; font-weight:600; transition:all 0.15s;
        }
        .popup-link:hover { background:var(--bg-input); color:var(--text-heading); }
        .popup-link i { width:18px; text-align:center; color:var(--text-muted); font-size:13px; }
        .popup-logout-form { display:block; }
        .popup-logout { width:100%; background:none; border:none; cursor:pointer; font-family:inherit; text-align:left; }
        .popup-logout:hover { background:rgba(231,76,60,0.1); color:#f87171; }
        .popup-logout:hover i { color:#f87171; }
        /* ── Global filter bar (shared across all list pages) ── */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            padding: 10px 0 14px;
        }
        .filter-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 5px;
            flex-shrink: 0;
        }
        .filter-chip {
            height: 34px;
            padding: 0 12px;
            background: var(--bg-input);
            border: 1px solid var(--border-card);
            border-radius: 8px;
            color: var(--text-heading);
            font-size: 12px;
            font-family: inherit;
            cursor: pointer;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394a3b8' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 28px;
        }
        .filter-chip:focus {
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56,189,248,0.12);
        }
        .filter-chip:hover { border-color: #38bdf8; }

        /* ── Global page-header with title + action button ── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 0 0 20px;
        }
        .page-header-title {
            font-size: 22px;
            font-weight: 800;
            color: var(--text-heading);
            margin: 0;
        }
        .page-header-meta {
            font-size: 12px;
            color: var(--text-muted);
            margin: 2px 0 0;
        }
        /* ── Alert styles (shared) ── */
        .alert {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 16px; border-radius: 10px;
            font-size: 13px; margin-bottom: 16px;
        }
        .alert-success {
            background: rgba(39,174,96,0.1); color: #27ae60;
            border: 1px solid rgba(39,174,96,0.2);
        }
        .alert-warning {
            background: rgba(243,156,18,0.1); color: #f39c12;
            border: 1px solid rgba(243,156,18,0.2);
        }
        .alert-danger {
            background: rgba(231,76,60,0.1); color: #e74c3c;
            border: 1px solid rgba(231,76,60,0.2);
        }
        /* ── CMC Pagination (global — applies inside Livewire too) ── */
        .cmc-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding: 14px 20px;
            border-top: 1px solid var(--border-inner);
        }
        .cmc-page-info {
            font-size: 13px;
            color: var(--text-muted);
            margin: 0;
            flex-shrink: 0;
        }
        .cmc-page-info-bold {
            font-weight: 700;
            color: var(--text-heading);
        }
        .cmc-page-buttons {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .cmc-page-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            border: 1px solid var(--border-card);
            background: var(--bg-input);
            color: var(--text-body);
            cursor: pointer;
            transition: all 0.15s;
            line-height: 1;
        }
        .cmc-page-btn-num:hover,
        .cmc-page-btn-nav:hover {
            border-color: #38bdf8;
            color: #38bdf8;
            background: rgba(56,189,248,0.08);
        }
        .cmc-page-btn-nav i { font-size: 11px; }
        .cmc-page-btn-active {
            background: linear-gradient(135deg, #2980b9, #1a6ea8);
            border-color: transparent;
            color: #fff !important;
            cursor: default;
            box-shadow: 0 2px 8px rgba(41,128,185,0.35);
        }
        .cmc-page-btn-disabled {
            opacity: 0.35;
            cursor: not-allowed;
            background: var(--bg-input);
        }
        .cmc-page-btn-disabled i { font-size: 11px; }
        .cmc-page-ellipsis {
            background: transparent;
            border-color: transparent;
            color: var(--text-muted);
            cursor: default;
            min-width: 24px;
            padding: 0 4px;
            font-size: 15px;
            letter-spacing: 1px;
        }
        .cmc-page-btn[disabled] { opacity: 0.5; cursor: wait; }
        .cmc-page-footer {
            display: flex;
            gap: 14px;
            padding: 8px 14px;
            border-top: 1px solid var(--border-inner);
            font-size: 10px;
            color: var(--text-muted);
        }
        @media (max-width: 480px) {
            .cmc-pagination { flex-direction: column; align-items: flex-start; gap: 10px; }
            .cmc-page-btn { min-width: 30px; height: 30px; font-size: 12px; }
        }
    </style>
</head>
<body data-theme="light">

    {{-- SIDEBAR OVERLAY (mobile only) --}}
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    {{-- GLASSMORPHISM SIDEBAR --}}
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
                <a href="{{ route('dashboard') }}#appointments" class="sidebar-link">
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
                @endif
                <a href="{{ route('forms.index') }}" class="sidebar-link {{ request()->routeIs('forms.*') ? 'active' : '' }}">
                    <i class="fas fa-file-contract sidebar-icon"></i> Forms
                </a>
                @if (auth()->user() && auth()->user()->role === 'clinic_nurse')
                    <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield sidebar-icon"></i> User Management
                    </a>
                    <a href="{{ route('clinic-staff.index') }}" class="sidebar-link {{ request()->routeIs('clinic-staff.*') ? 'active' : '' }}">
                        <i class="fas fa-user-md sidebar-icon"></i> Clinic Staff
                    </a>
                    <a href="{{ route('recently-deleted.index') }}" class="sidebar-link {{ request()->routeIs('recently-deleted.*') ? 'active' : '' }}">
                        <i class="fas fa-trash-restore sidebar-icon"></i> Recently Deleted
                    </a>
                @endif
                <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="fas fa-sliders-h sidebar-icon"></i> Settings
                </a>
            @endif
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

    {{-- MAIN WRAPPER (margin-left 256px on desktop via CSS) --}}
    <div class="app-wrapper">
        <main class="app-main">

            {{-- TOPBAR (no page title) --}}
            <div class="app-topbar">
                {{-- Hamburger: hidden on desktop via CSS, shown on mobile --}}
                <button type="button" class="sidebar-toggle-btn" id="sidebarToggle" title="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>

                {{-- Global search (Livewire command-palette) --}}
                @livewire('global-search')

                {{-- Right: theme + notif + user --}}
                @php
                    $pendingApprovalCount = \App\Models\User::where('approval_status', 'pending')->where('id', '!=', 1)->count() + \App\Models\PendingRegistration::count();
                    $pendingAppointmentCount = \App\Models\Appointment::where('status', 'scheduled')->where('appointment_date', '>=', now()->toDateString())->count();
                    $lowStockCount = \App\Models\Medicine::whereRaw('quantity <= minimum_stock')->where('status', 'active')->count();
                    $deletedNotificationCount = \App\Models\Patient::onlyTrashed()->count()
                        + \App\Models\ClinicVisit::onlyTrashed()->count()
                        + \App\Models\Appointment::onlyTrashed()->count();
                    $totalSystemNotifications = $pendingApprovalCount + $pendingAppointmentCount + $lowStockCount + $deletedNotificationCount;
                    $notificationItems = [];

                    if ($pendingApprovalCount > 0) {
                        $notificationItems[] = [
                            'route' => route('users.index'),
                            'icon' => 'fa-user-check',
                            'title' => $pendingApprovalCount . ' pending approval' . ($pendingApprovalCount > 1 ? 's' : ''),
                            'subtitle' => 'Review new account requests',
                        ];
                    }

                    if ($pendingAppointmentCount > 0) {
                        $notificationItems[] = [
                            'route' => route('appointments.index'),
                            'icon' => 'fa-calendar-check',
                            'title' => $pendingAppointmentCount . ' upcoming appointment' . ($pendingAppointmentCount > 1 ? 's' : ''),
                            'subtitle' => 'Check schedule and follow-up queue',
                        ];
                    }

                    if ($lowStockCount > 0) {
                        $notificationItems[] = [
                            'route' => route('medicines.index'),
                            'icon' => 'fa-pills',
                            'title' => $lowStockCount . ' low-stock medicine' . ($lowStockCount > 1 ? 's' : ''),
                            'subtitle' => 'Restock needed soon',
                        ];
                    }

                    if ($deletedNotificationCount > 0) {
                        $notificationItems[] = [
                            'route' => route('recently-deleted.index'),
                            'icon' => 'fa-trash-restore',
                            'title' => $deletedNotificationCount . ' deleted record' . ($deletedNotificationCount > 1 ? 's' : ''),
                            'subtitle' => 'Recover recent removals',
                        ];
                    }
                @endphp
                <div class="topbar-right">
                    <button type="button" class="theme-toggle-btn" id="themeToggle" title="Toggle theme">
                        <i class="fas fa-sun"></i>
                    </button>
                    <div class="notification-wrapper">
                        <button type="button" class="topbar-icon-btn" id="notificationToggle" title="System Notifications" style="display:inline-flex; align-items:center; justify-content:center; text-decoration:none;">
                            <i class="fas fa-bell"></i>
                            @if($totalSystemNotifications > 0)
                                <span class="notification-badge">{{ $totalSystemNotifications > 9 ? '9+' : $totalSystemNotifications }}</span>
                            @endif
                        </button>
                        <div class="notification-dropdown" id="notificationDropdown">
                            <div class="notification-header">
                                <span>System Alerts</span>
                                <span class="count-pill">{{ $totalSystemNotifications }}</span>
                            </div>
                            @if(count($notificationItems) > 0)
                                <div class="notification-list">
                                    @foreach($notificationItems as $item)
                                        <a href="{{ $item['route'] }}" class="notification-item">
                                            <span class="notification-icon"><i class="fas {{ $item['icon'] }}"></i></span>
                                            <span class="notification-text">
                                                <strong>{{ $item['title'] }}</strong>
                                                <small>{{ $item['subtitle'] }}</small>
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="notification-empty">No active alerts.</div>
                            @endif
                        </div>
                    </div>
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

            {{-- PAGE CONTENT --}}
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
        /* ── Sidebar: desktop = always visible via CSS; mobile = JS toggle ── */
        (function () {
            var sidebar   = document.getElementById('clinicSidebar');
            var overlay   = document.getElementById('sidebarOverlay');
            var toggleBtn = document.getElementById('sidebarToggle');

            function isMobile() { return window.innerWidth < 1024; }
            function openMobile()  { sidebar.classList.add('sidebar-open'); overlay.classList.add('overlay-visible'); }
            function closeMobile() { sidebar.classList.remove('sidebar-open'); overlay.classList.remove('overlay-visible'); }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    if (!isMobile()) return;
                    sidebar.classList.contains('sidebar-open') ? closeMobile() : openMobile();
                });
            }
            if (overlay) overlay.addEventListener('click', closeMobile);

            window.addEventListener('resize', function () {
                if (!isMobile()) {
                    overlay.classList.remove('overlay-visible');
                    sidebar.classList.remove('sidebar-open');
                }
            });
        })();

        /* ── Theme toggle ── */
        (function () {
            var body  = document.body;
            var btn   = document.getElementById('themeToggle');
            var saved = localStorage.getItem('clinicTheme') || 'light';
            body.setAttribute('data-theme', saved);
            btn.innerHTML = saved === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
            btn.addEventListener('click', function () {
                var next = body.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                body.setAttribute('data-theme', next);
                localStorage.setItem('clinicTheme', next);
                btn.innerHTML = next === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
                window.dispatchEvent(new CustomEvent('clinic-theme-changed', { detail: { theme: next } }));
            });
        })();

        /* ── Notification dropdown ── */
        (function () {
            var toggle = document.getElementById('notificationToggle');
            var dropdown = document.getElementById('notificationDropdown');
            if (!toggle || !dropdown) return;

            toggle.addEventListener('click', function (e) {
                e.stopPropagation();
                var isOpen = dropdown.classList.contains('open');
                dropdown.classList.toggle('open', !isOpen);
            });

            document.addEventListener('click', function (e) {
                if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('open');
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') dropdown.classList.remove('open');
            });
        })();

        /* ── Profile popup ── */
        (function () {
            var trigger = document.getElementById('topbarUserProfile');
            var popup   = document.getElementById('profilePopup');
            if (!trigger || !popup) return;
            trigger.addEventListener('click', function (e) { e.stopPropagation(); popup.classList.toggle('open'); });
            document.addEventListener('click', function (e) { if (!trigger.contains(e.target)) popup.classList.remove('open'); });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') popup.classList.remove('open'); });
        })();
    </script>

    @livewireScripts
</body>
</html>