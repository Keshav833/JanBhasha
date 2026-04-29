<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'JanBhasha') }} — Government Translation Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Sans+Devanagari:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        hindi: ['Noto Sans Devanagari', 'sans-serif'],
                    },
                    colors: {
                        saffron: { 50:'#fff7ed', 100:'#ffedd5', 200:'#fed7aa', 300:'#fdba74', 400:'#fb923c', 500:'#f97316', 600:'#ea580c', 700:'#c2410c', 800:'#9a3412', 900:'#7c2d12' },
                        ashoka: { 50:'#eff6ff', 100:'#dbeafe', 200:'#bfdbfe', 300:'#93c5fd', 400:'#60a5fa', 500:'#3b82f6', 600:'#2563eb', 700:'#1d4ed8', 800:'#1e40af', 900:'#1e3a8a' },
                    }
                }
            }
        }
    </script>
    <style>
        body { background: #f8fafc; }
        .sidebar { background: linear-gradient(180deg, #1e3a8a 0%, #1d4ed8 60%, #2563eb 100%); }
        .stat-card { background: white; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.04); transition: transform .2s, box-shadow .2s; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 24px rgba(0,0,0,.1); }
        .nav-link { border-radius: 10px; transition: all .15s; }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,.15); }
        .nav-link.active { background: rgba(255,255,255,.2); font-weight: 600; }
        .btn-primary { background: linear-gradient(135deg, #f97316, #ea580c); color: white; border-radius: 10px; padding: .625rem 1.5rem; font-weight: 600; transition: all .2s; box-shadow: 0 2px 8px rgba(249,115,22,.3); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(249,115,22,.4); }
        .btn-secondary { background: white; border: 1.5px solid #e2e8f0; color: #374151; border-radius: 10px; padding: .625rem 1.5rem; font-weight: 500; transition: all .2s; }
        .btn-secondary:hover { border-color: #93c5fd; color: #1d4ed8; }
        .input-field { border: 1.5px solid #e2e8f0; border-radius: 10px; padding: .625rem 1rem; width: 100%; transition: border-color .2s, box-shadow .2s; font-size: .95rem; background: white; }
        .input-field:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.1); }
        textarea.input-field { resize: vertical; min-height: 140px; font-family: inherit; }
        .badge { display: inline-flex; align-items: center; padding: .2rem .65rem; border-radius: 99px; font-size: .75rem; font-weight: 600; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-error   { background: #fee2e2; color: #dc2626; }
        .badge-warning { background: #fef3c7; color: #d97706; }
        .table-row:hover { background: #f8fafc; }
        .card { background: white; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.04); }
        .ashoka-wheel { width: 36px; height: 36px; border-radius: 50%; border: 3px solid #fff; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
        .quota-bar { height: 8px; border-radius: 99px; background: #e2e8f0; overflow: hidden; }
        .quota-fill { height: 100%; border-radius: 99px; background: linear-gradient(90deg, #3b82f6, #2563eb); transition: width .5s ease; }
        .translation-box { border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 1.25rem; background: #f8fafc; min-height: 140px; font-family: 'Noto Sans Devanagari', sans-serif; font-size: 1.05rem; line-height: 1.8; color: #1e293b; }
        .flash-success { background: #dcfce7; border: 1px solid #86efac; color: #15803d; border-radius: 10px; padding: .75rem 1.25rem; }
        .flash-error   { background: #fee2e2; border: 1px solid #fca5a5; color: #dc2626; border-radius: 10px; padding: .75rem 1.25rem; }
        @keyframes fadeInUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
        .fade-in { animation: fadeInUp .35s ease both; }
    </style>
</head>
<body class="h-full font-sans antialiased">
<div class="flex h-screen overflow-hidden">

    {{-- ── Sidebar ─────────────────────────────── --}}
    <aside class="sidebar w-64 flex-shrink-0 flex flex-col text-white">
        {{-- Logo --}}
        <div class="px-6 py-5 flex items-center gap-3 border-b border-white/10">
            <div class="ashoka-wheel bg-saffron-500">🇮🇳</div>
            <div>
                <div class="font-bold text-lg leading-tight">JanBhasha</div>
                <div class="text-xs text-blue-200">जनभाषा — Govt. Portal</div>
            </div>
        </div>

        {{-- Org badge --}}
        @auth
        @if(auth()->user()->organisation)
        <div class="mx-4 mt-4 mb-1 bg-white/10 rounded-xl px-4 py-3">
            <div class="text-xs text-blue-200 uppercase tracking-wide font-medium mb-0.5">Organisation</div>
            <div class="font-semibold text-sm leading-tight">{{ auth()->user()->organisation->name }}</div>
        </div>
        @endif
        @endauth

        {{-- Nav --}}
        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 text-sm">
                <span class="text-lg">🏠</span> Dashboard
            </a>
            <a href="{{ route('translations.create') }}" class="nav-link {{ request()->routeIs('translations.create') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 text-sm">
                <span class="text-lg">✍️</span> Translate
            </a>
            <a href="{{ route('translations.index') }}" class="nav-link {{ request()->routeIs('translations.index') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 text-sm">
                <span class="text-lg">📋</span> History
            </a>
            <a href="{{ route('glossary.index') }}" class="nav-link {{ request()->routeIs('glossary.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 text-sm">
                <span class="text-lg">📖</span> Glossary
            </a>

            @auth
            @if(auth()->user()->isSuperAdmin())
            <div class="pt-3 pb-1 px-4 text-xs text-blue-300 uppercase tracking-wide font-medium">⚡ Admin</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 text-sm">
                <span class="text-lg">🎛️</span> Admin Dashboard
            </a>
            <a href="{{ route('admin.organisations.index') }}" class="nav-link {{ request()->routeIs('admin.organisations.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 text-sm">
                <span class="text-lg">🏛️</span> Organisations
            </a>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 text-sm">
                <span class="text-lg">👥</span> Users
            </a>
            @endif
            @endauth
        </nav>

        {{-- User footer --}}
        @auth
        <div class="border-t border-white/10 px-4 py-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-saffron-500 flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-blue-300 truncate">{{ auth()->user()->email }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Logout" class="text-blue-300 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </aside>

    {{-- ── Main content ─────────────────────────── --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Top bar --}}
        <header class="bg-white border-b border-gray-100 px-8 py-4 flex items-center justify-between flex-shrink-0">
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $header ?? 'Dashboard' }}</h1>
                <p class="text-xs text-gray-400 mt-0.5">{{ now()->format('l, d F Y') }}</p>
            </div>
            <a href="{{ route('translations.create') }}" class="btn-primary text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                New Translation
            </a>
        </header>

        {{-- Flash messages --}}
        <div class="px-8 pt-4">
            @if(session('success'))
            <div class="flash-success mb-0 flex items-center gap-2 fade-in">
                <span>✅</span> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="flash-error mb-0 flex items-center gap-2 fade-in">
                <span>❌</span> {{ session('error') }}
            </div>
            @endif
        </div>

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto px-8 py-6">
            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>
