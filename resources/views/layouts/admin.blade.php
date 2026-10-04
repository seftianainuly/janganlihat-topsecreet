<!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: window.innerWidth >= 768 }" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — GadgetHub CMS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Space Grotesk', sans-serif; }
        .font-orbitron { font-family: 'Orbitron', sans-serif; }

        .gradient-text {
            background: linear-gradient(135deg, #06b6d4, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #0c1526 100%);
            border-right: 1px solid rgba(6,182,212,0.15);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #94a3b8;
            transition: all 0.2s ease;
            margin-bottom: 2px;
        }
        .sidebar-link:hover { background: rgba(6,182,212,0.1); color: #06b6d4; }
        .sidebar-link.active { background: rgba(6,182,212,0.15); color: #06b6d4; border-left: 2px solid #06b6d4; }

        .admin-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 0.75rem;
        }

        .stat-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(6,182,212,0.2);
            border-radius: 0.75rem;
            padding: 1.25rem;
            transition: all 0.3s ease;
        }
        .stat-card:hover { border-color: rgba(6,182,212,0.5); box-shadow: 0 0 20px rgba(6,182,212,0.1); }

        .topbar {
            background: rgba(15,23,42,0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(6,182,212,0.1);
        }

        /* Table styles */
        .data-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .data-table thead th {
            background: rgba(6,182,212,0.08);
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #06b6d4;
            border-bottom: 1px solid rgba(6,182,212,0.2);
        }
        .data-table tbody tr {
            border-bottom: 1px solid rgba(255,255,255,0.05);
            transition: background 0.2s ease;
        }
        .data-table tbody tr:hover { background: rgba(6,182,212,0.04); }
        .data-table tbody td { padding: 0.875rem 1rem; font-size: 0.875rem; color: #cbd5e1; vertical-align: middle; }

        /* Badge */
        .badge-published { background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.3); }
        .badge-draft     { background: rgba(100,116,139,0.15); color: #94a3b8; border: 1px solid rgba(100,116,139,0.3); }

        /* Form inputs */
        .form-input {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 0.5rem;
            padding: 0.625rem 0.875rem;
            color: #e2e8f0;
            font-size: 0.875rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .form-input:focus {
            outline: none;
            border-color: #06b6d4;
            box-shadow: 0 0 0 3px rgba(6,182,212,0.1);
        }
        .form-input::placeholder { color: #475569; }
        select.form-input option { background: #1e293b; color: #e2e8f0; }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: 0.4rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        /* Scrollbar */
        ::-webkit-scrollbar       { width: 5px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #06b6d4; }

        /* Btn variants */
        .btn-primary {
            background: linear-gradient(135deg, #06b6d4, #8b5cf6);
            color: white; padding: 0.55rem 1.25rem; border-radius: 0.5rem;
            font-weight: 600; font-size: 0.875rem;
            transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.4rem;
        }
        .btn-primary:hover { opacity: 0.85; box-shadow: 0 0 15px rgba(6,182,212,0.3); }
        .btn-danger  { background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3); padding: 0.4rem 0.875rem; border-radius: 0.4rem; font-size: 0.8rem; font-weight: 600; transition: all 0.2s; }
        .btn-danger:hover { background: rgba(239,68,68,0.25); }
        .btn-edit    { background: rgba(6,182,212,0.15); color: #06b6d4; border: 1px solid rgba(6,182,212,0.3); padding: 0.4rem 0.875rem; border-radius: 0.4rem; font-size: 0.8rem; font-weight: 600; transition: all 0.2s; }
        .btn-edit:hover { background: rgba(6,182,212,0.25); }
        .btn-toggle-pub  { background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.3); padding: 0.4rem 0.875rem; border-radius: 0.4rem; font-size: 0.75rem; font-weight: 600; transition: all 0.2s; }
        .btn-toggle-pub:hover  { background: rgba(16,185,129,0.25); }
        .btn-toggle-draft { background: rgba(139,92,246,0.15); color: #a78bfa; border: 1px solid rgba(139,92,246,0.3); padding: 0.4rem 0.875rem; border-radius: 0.4rem; font-size: 0.75rem; font-weight: 600; transition: all 0.2s; }
        .btn-toggle-draft:hover { background: rgba(139,92,246,0.25); }
    </style>

    @stack('styles')
</head>

<body class="admin-page relative flex h-screen overflow-hidden">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar shrink-0 flex flex-col transition-all duration-300 z-40"
           :class="sidebarOpen ? 'w-60' : 'w-16'">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-4 h-16 border-b border-slate-800">
            <div class="w-8 h-8 shrink-0 rounded-lg bg-linear-to-br from-cyan-400 to-violet-500 flex items-center justify-center">
                <span class="text-white font-bold text-sm font-orbitron">G</span>
            </div>
            <span x-show="sidebarOpen" x-transition class="font-orbitron font-bold text-base gradient-text whitespace-nowrap">GadgetHub</span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 p-3 overflow-y-auto">
            <a href="{{ route('admin.blogs.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Artikel Blog</span>
            </a>

            <a href="{{ route('blog.index') }}" target="_blank"
               class="sidebar-link">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Lihat Website</span>
            </a>
        </nav>

        {{-- User & Logout --}}
        <div class="p-3 border-t border-slate-800">
            <div class="flex items-center gap-3 px-2 mb-3" x-show="sidebarOpen">
                <div class="w-8 h-8 rounded-full bg-linear-to-br from-cyan-400 to-violet-500 flex items-center justify-center shrink-0">
                    <span class="text-white text-xs font-bold">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</span>
                </div>
                <div x-show="sidebarOpen">
                    <p class="text-xs font-semibold text-slate-200 truncate max-w-32">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-slate-500 truncate max-w-32">{{ Auth::user()->email ?? '' }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="sidebar-link w-full text-red-400 hover:text-red-300! hover:bg-red-900/20!">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span x-show="sidebarOpen">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Topbar --}}
        <header class="topbar h-16 flex items-center justify-between px-6 shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="p-2 rounded-lg text-slate-400 hover:text-cyan-400 hover:bg-slate-800 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h1 class="font-orbitron font-bold text-sm gradient-text">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-slate-500">@yield('page-subtitle', 'GadgetHub CMS')</p>
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                 class="flash-success mx-6 mt-4 flex items-center gap-3 px-4 py-3 rounded-lg bg-emerald-900/50 border border-emerald-500/50 text-emerald-300 text-sm">
                <span>✅</span>
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="ml-auto text-emerald-400 hover:text-white">✕</button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                 class="flash-error mx-6 mt-4 flex items-center gap-3 px-4 py-3 rounded-lg bg-red-900/50 border border-red-500/50 text-red-300 text-sm">
                <span>❌</span>
                <span>{{ session('error') }}</span>
                <button @click="show = false" class="ml-auto text-red-400 hover:text-white">✕</button>
            </div>
        @endif

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>

    <style>
        @keyframes pulseGlow {
            0%,100% { box-shadow: 0 0 5px rgba(16,185,129,0.4); }
            50%      { box-shadow: 0 0 15px rgba(16,185,129,0.8); }
        }
        .pulse-glow { animation: pulseGlow 2s ease-in-out infinite; }

        body.admin-page { background:#f6f7fb; color:#344054; }
        .admin-page, .admin-page .font-orbitron { font-family:'DM Sans',sans-serif; }
        .admin-page .sidebar { background:#fff; border-right:1px solid #e7eaf0; }
        .admin-page .sidebar-link { color:#667085; }
        .admin-page .sidebar-link:hover { background:#f4f3ff; color:#5146c8; }
        .admin-page .sidebar-link.active { background:#f0efff; color:#5146c8; border-left:2px solid #6258dd; }
        .admin-page .admin-card, .admin-page .stat-card { background:#fff; border:1px solid #e7eaf0; border-radius:.8rem; box-shadow:0 2px 8px rgb(16 24 40 / 4%); }
        .admin-page .stat-card:hover { border-color:#d6d3ff; box-shadow:0 5px 14px rgb(16 24 40 / 6%); }
        .admin-page .topbar { background:#fff; border-bottom:1px solid #e7eaf0; }
        .admin-page .gradient-text { color:#5146c8; background:none; -webkit-text-fill-color:currentColor; }
        .admin-page .text-white { color:#172033 !important; }
        .admin-page .text-slate-200, .admin-page .text-slate-300 { color:#344054 !important; }
        .admin-page .text-slate-400, .admin-page .text-slate-500, .admin-page .text-slate-600 { color:#667085 !important; }
        .admin-page .border-slate-800, .admin-page .border-slate-700 { border-color:#e7eaf0 !important; }
        .admin-page .bg-slate-950, .admin-page .bg-slate-900, .admin-page .bg-slate-800 { background:#f8f9fc !important; }
        .admin-page .data-table thead th { background:#f8f9fc; color:#667085; border-bottom:1px solid #e7eaf0; letter-spacing:.04em; }
        .admin-page .data-table tbody tr { border-bottom:1px solid #f0f1f5; }
        .admin-page .data-table tbody tr:hover { background:#fafaff; }
        .admin-page .data-table tbody td { color:#475467; }
        .admin-page .form-input { background:#fff; border-color:#d0d5dd; color:#172033; border-radius:.6rem; }
        .admin-page .form-input.pl-9 { padding-left:2.25rem; }
        .admin-page select.form-input { width:auto; flex:0 0 10rem; }
        .admin-page .form-input::placeholder { color:#98a2b3; }
        .admin-page .form-input:focus { border-color:#827cff; box-shadow:0 0 0 3px rgb(80 72 216 / 10%); }
        .admin-page select.form-input option { background:#fff; color:#172033; }
        .admin-page .form-label { color:#475467; letter-spacing:0; text-transform:none; }
        .admin-page .category-pill { background:#eeedff; border-color:#e4e2ff; color:#5146c8; }
        .admin-page .btn-primary { background:#5146c8; border-radius:.6rem; box-shadow:none; }
        .admin-page .btn-primary:hover { background:#3931bc; opacity:1; }
        .admin-page .btn-danger { background:#fff5f5; color:#c24141; border-color:#f5d3d3; }
        .admin-page .btn-edit { background:#f4f3ff; color:#5146c8; border-color:#e4e2ff; }
        .admin-page .btn-toggle-pub { background:#f0fdf4; color:#15803d; border-color:#bbf7d0; }
        .admin-page .btn-toggle-draft { background:#f8f7ff; color:#675fc5; border-color:#e4e2ff; }
        .admin-page .badge-published { background:#ecfdf3; color:#15803d; border-color:#abefc6; }
        .admin-page .badge-draft { background:#f2f4f7; color:#667085; border-color:#eaecf0; }
        .admin-page .flash-success { background:#ecfdf3; border-color:#abefc6; color:#15803d; }
        .admin-page .flash-error { background:#fff5f5; border-color:#f5d3d3; color:#c24141; }
        .admin-page .pulse-glow { animation:none; }
        .admin-page .sidebar .bg-gradient-to-br { background:#5146c8; box-shadow:none; }
        .admin-page .sidebar .font-orbitron { font-family:'Manrope',sans-serif; }
        .admin-page .sidebar .sidebar-link { border-radius:.55rem; }
        .admin-page .data-table thead th { font-size:.68rem; }
        .admin-page .data-table tbody td { padding:.8rem .75rem; }
        .admin-page .data-table { table-layout:fixed; }
        .admin-page .data-table th:first-child, .admin-page .data-table td:first-child { width:3rem; }
        .admin-page .data-table th:nth-child(5), .admin-page .data-table td:nth-child(5) { width:7.5rem; }
        .admin-page .data-table th:last-child, .admin-page .data-table td:last-child { width:12rem; }
        .admin-page .data-table tbody td:last-child { min-width:11rem; }
        .admin-page .data-table tbody td:last-child > div { flex-wrap:nowrap; gap:.35rem; }
        .admin-page .btn-danger, .admin-page .btn-edit, .admin-page .btn-toggle-pub, .admin-page .btn-toggle-draft { padding:.35rem .5rem; font-size:.68rem; white-space:nowrap; }
        .admin-page .admin-card { box-shadow:0 1px 3px rgb(16 24 40 / 4%); }
        @media (max-width:640px) {
            .admin-page .sidebar { position:absolute; top:0; bottom:0; left:0; box-shadow:0 1px 8px rgb(16 24 40 / 8%); }
            .admin-page > div.flex-1 { min-width:0; margin-left:4rem; }
            .admin-page main { padding:1rem; }
            .admin-page .topbar { padding-inline:1rem; }
            .admin-page select.form-input { width:100%; flex-basis:auto; }
            .admin-page .data-table th:first-child, .admin-page .data-table td:first-child { display:none; }
            .admin-page .data-table th:nth-child(5), .admin-page .data-table td:nth-child(5) { width:6rem; }
            .admin-page .data-table th:last-child, .admin-page .data-table td:last-child { width:9rem; min-width:9rem; }
        }
    </style>

    @stack('scripts')
</body>
</html>
