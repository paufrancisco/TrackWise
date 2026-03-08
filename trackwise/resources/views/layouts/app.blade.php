<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TrackWise &mdash; @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'DM Sans', sans-serif; box-sizing: border-box; }

        :root {
            --sidebar-w: 240px;
            --bg: #0f1117;
            --surface: #16181f;
            --surface2: #1e2029;
            --border: #2a2d3a;
            --accent: #6ee7b7;
            --accent2: #f87171;
            --accent3: #60a5fa;
            --text: #e8eaf0;
            --muted: #6b7280;
        }

        body { background: var(--bg); color: var(--text); min-height: 100vh; margin: 0; }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w); height: 100vh;
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column; z-index: 50;
        }
        .sidebar-logo { padding: 28px 24px 20px; border-bottom: 1px solid var(--border); }
        .sidebar-logo span { font-size: 18px; font-weight: 600; letter-spacing: -0.5px; color: var(--text); }
        .sidebar-logo span em { font-style: normal; color: var(--accent); }
        .sidebar-nav { flex: 1; padding: 16px 12px; display: flex; flex-direction: column; gap: 4px; }
        .nav-label {
            font-size: 10px; font-weight: 600; letter-spacing: 1.5px;
            text-transform: uppercase; color: var(--muted);
            padding: 8px 12px 4px; margin-top: 8px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 8px;
            color: var(--muted); text-decoration: none;
            font-size: 14px; font-weight: 500; transition: all 0.15s;
            border: 1px solid transparent;
        }
        .nav-item:hover { background: var(--surface2); color: var(--text); }
        .nav-item.active { background: rgba(110,231,183,0.08); color: var(--accent); border-color: rgba(110,231,183,0.15); }
        .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; }
        .sidebar-footer { padding: 16px 12px; border-top: 1px solid var(--border); }
        .user-info {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 8px; background: var(--surface2);
        }
        .avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent3));
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: #0f1117; flex-shrink: 0;
        }
        .user-name { font-size: 13px; font-weight: 500; color: var(--text); flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        /* ── Main ── */
        .main-wrap { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }
        .topbar {
            height: 60px; border-bottom: 1px solid var(--border);
            background: var(--surface); display: flex; align-items: center;
            justify-content: space-between; padding: 0 32px;
            position: sticky; top: 0; z-index: 40;
        }
        .page-title { font-size: 15px; font-weight: 600; color: var(--text); }
        .topbar-actions { display: flex; align-items: center; gap: 12px; }
        .content { padding: 32px; flex: 1; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px;
            font-size: 13px; font-weight: 500; cursor: pointer;
            border: none; text-decoration: none; transition: all 0.15s;
            font-family: 'DM Sans', sans-serif;
        }
        .btn-primary { background: var(--accent); color: #0f1117; }
        .btn-primary:hover { background: #34d399; }
        .btn-ghost { background: var(--surface2); color: var(--muted); border: 1px solid var(--border); }
        .btn-ghost:hover { color: var(--text); border-color: #4b5563; }
        .btn-danger { background: rgba(248,113,113,0.1); color: var(--accent2); border: 1px solid rgba(248,113,113,0.2); }
        .btn-danger:hover { background: rgba(248,113,113,0.2); }
        .btn-sm { padding: 5px 12px; font-size: 12px; }

        /* ── Flash ── */
        .flash-success {
            background: rgba(110,231,183,0.08); border: 1px solid rgba(110,231,183,0.2);
            color: var(--accent); padding: 12px 20px; border-radius: 8px;
            font-size: 13px; margin: 16px 32px 0;
        }

        /* ── Cards ── */
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
        .card-pad { padding: 24px; }
        .card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 24px; border-bottom: 1px solid var(--border);
        }
        .card-title { font-size: 13px; font-weight: 600; color: var(--text); }

        /* ── Stat Cards ── */
        .stat-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 12px; padding: 24px; position: relative; overflow: hidden;
        }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; }
        .stat-income::before { background: var(--accent); }
        .stat-expense::before { background: var(--accent2); }
        .stat-balance::before { background: var(--accent3); }
        .stat-label { font-size: 11px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; color: var(--muted); margin-bottom: 12px; }
        .stat-value { font-size: 26px; font-weight: 600; letter-spacing: -1px; font-family: 'DM Mono', monospace; }
        .stat-income .stat-value { color: var(--accent); }
        .stat-expense .stat-value { color: var(--accent2); }
        .stat-balance .stat-value { color: var(--accent3); }
        .stat-sub { font-size: 12px; color: var(--muted); margin-top: 6px; }

        /* ── Table ── */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            text-align: left; font-size: 11px; font-weight: 600;
            letter-spacing: 0.8px; text-transform: uppercase; color: var(--muted);
            padding: 12px 16px; border-bottom: 1px solid var(--border);
        }
        .data-table th:last-child, .data-table td:last-child { text-align: center; }
        .data-table th.text-right, .data-table td.text-right { text-align: right; }
        .data-table td { padding: 14px 16px; font-size: 13px; border-bottom: 1px solid rgba(42,45,58,0.5); }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background: rgba(255,255,255,0.02); }

        /* ── Badges ── */
        .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-income { background: rgba(110,231,183,0.1); color: var(--accent); }
        .badge-expense { background: rgba(248,113,113,0.1); color: var(--accent2); }

        /* ── Amount ── */
        .amount { font-family: 'DM Mono', monospace; font-size: 13px; }
        .amount-income { color: var(--accent); }
        .amount-expense { color: var(--accent2); }

        /* ── Forms ── */
        .form-input, .form-select, .form-textarea {
            width: 100%; background: var(--surface2);
            border: 1px solid var(--border); border-radius: 8px;
            padding: 10px 14px; color: var(--text);
            font-size: 14px; font-family: 'DM Sans', sans-serif;
            transition: border-color 0.15s; outline: none;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--accent); }
        .form-input::placeholder { color: var(--muted); }
        .form-select option { background: var(--surface2); }
        .form-textarea { resize: vertical; }
        .field-label { display: block; font-size: 11px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; color: var(--muted); margin-bottom: 6px; }
        .field-error { color: var(--accent2); font-size: 12px; margin-top: 4px; }
        .form-input.has-error, .form-select.has-error { border-color: var(--accent2); }

        /* ── Type selector ── */
        .type-selector { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .type-option { position: relative; }
        .type-option input { position: absolute; opacity: 0; width: 0; height: 0; }
        .type-label {
            display: block; padding: 12px; border-radius: 8px;
            border: 1px solid var(--border); text-align: center;
            font-size: 13px; font-weight: 600; color: var(--muted);
            cursor: pointer; transition: all 0.15s;
        }
        .type-option input:checked + .income-label { background: rgba(110,231,183,0.1); border-color: var(--accent); color: var(--accent); }
        .type-option input:checked + .expense-label { background: rgba(248,113,113,0.1); border-color: var(--accent2); color: var(--accent2); }

        /* ── Filter bar ── */
        .filter-bar {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 12px; padding: 16px 20px;
            display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .filter-bar .form-input, .filter-bar .form-select { width: auto; flex: 1; min-width: 140px; }

        /* ── Pagination override ── */
        .pagination-wrap { margin-top: 16px; }
        .pagination-wrap nav { display: flex; justify-content: flex-end; }
        .pagination-wrap span[aria-current="page"] > span,
        .pagination-wrap a {
            background: var(--surface2) !important;
            border-color: var(--border) !important;
            color: var(--muted) !important;
            font-size: 13px !important;
        }
        .pagination-wrap a:hover { color: var(--text) !important; }
        .pagination-wrap span[aria-current="page"] > span {
            background: rgba(110,231,183,0.1) !important;
            border-color: var(--accent) !important;
            color: var(--accent) !important;
        }

        /* ── Empty state ── */
        .empty-state { text-align: center; padding: 48px 24px; color: var(--muted); font-size: 14px; }
        .empty-icon { font-size: 36px; margin-bottom: 12px; opacity: 0.4; }

        /* ── Export dropdown ── */
        .export-wrap { position: relative; }
        .export-menu {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            min-width: 168px;
            overflow: hidden;
            z-index: 100;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
        }
        .export-menu.open { display: block; }
        .export-menu-item {
            display: flex; align-items: center; gap: 10px;
            padding: 11px 16px; color: var(--text); text-decoration: none;
            font-size: 13px; font-weight: 500; transition: background 0.1s;
        }
        .export-menu-item:hover { background: var(--surface2); }
        .export-divider { height: 1px; background: var(--border); }
    </style>
</head>
<body>

{{-- Sidebar --}}
<aside class="sidebar">
    <div class="sidebar-logo">
        <span>Track<em>Wise</em></span>
    </div>
    <nav class="sidebar-nav">
        <span class="nav-label">Overview</span>
        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>
        <span class="nav-label">Transactions</span>
        <a href="{{ route('transactions.index') }}"
           class="nav-item {{ request()->routeIs('transactions.index') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            All Transactions
        </a>
        <a href="{{ route('transactions.create') }}"
           class="nav-item {{ request()->routeIs('transactions.create') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Transaction
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <span class="user-name">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Logout"
                        style="background:none;border:none;cursor:pointer;color:var(--muted);padding:0;display:flex;">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- Main --}}
<div class="main-wrap">
    <header class="topbar">
        <span class="page-title">@yield('title', 'Dashboard')</span>
        <div class="topbar-actions">

            {{-- Export Dropdown --}}
            <div class="export-wrap" id="exportWrap">
                <button class="btn btn-ghost" id="exportBtn">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export
                    <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="export-menu" id="exportMenu">
                    <a href="{{ route('transactions.export.pdf') }}" class="export-menu-item">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#f87171" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Export as PDF
                    </a>
                    <div class="export-divider"></div>
                    <a href="{{ route('transactions.export.excel') }}" class="export-menu-item">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#6ee7b7" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 10h18M3 14h18M10 3v18M14 3v18M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                        </svg>
                        Export as Excel
                    </a>
                </div>
            </div>

            <a href="{{ route('transactions.create') }}" class="btn btn-primary">+ New Transaction</a>
        </div>
    </header>

    @if (session('success'))
        <div class="flash-success">{{ session('success') }}</div>
    @endif

    <main class="content">
        @yield('content')
    </main>
</div>

<script>
  const exportBtn = document.getElementById('exportBtn')
  const exportMenu = document.getElementById('exportMenu')
  const exportWrap = document.getElementById('exportWrap')

  exportBtn.addEventListener('click', (e) => {
    e.stopPropagation()
    exportMenu.classList.toggle('open')
  })

  document.addEventListener('click', (e) => {
    if (!exportWrap.contains(e.target)) {
      exportMenu.classList.remove('open')
    }
  })
</script>
@stack('scripts')
</body>
</html>