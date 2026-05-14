<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'JanBhasha') }} — Government Translation Portal</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Sans+Devanagari:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Driver.js for guided tour -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #0b1120; color: #e2e8f0; }

        /* ── Sidebar ── */
        .sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #0f1f3d 100%);
            border-right: 1px solid rgba(37,99,235,0.2);
        }
        .nav-link { border-radius: 10px; transition: all .18s; color: #94a3b8; }
        .nav-link:hover { background: rgba(37,99,235,0.15); color: #e2e8f0; }
        .nav-link.active { background: rgba(37,99,235,0.25); color: #60a5fa; font-weight: 600; border-left: 3px solid #3b82f6; }

        /* ── Header ── */
        header { background: rgba(11,17,32,0.95); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(37,99,235,0.15); }

        /* ── Cards ── */
        .stat-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 16px; transition: transform .2s, box-shadow .2s, border-color .2s; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 32px rgba(37,99,235,0.15); border-color: rgba(37,99,235,0.3); }
        .card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 16px; }

        /* ── Buttons ── */
        .btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; border-radius: 10px; padding: .625rem 1.5rem; font-weight: 600; transition: all .2s; box-shadow: 0 4px 16px rgba(37,99,235,0.35), inset 0 1px 0 rgba(255,255,255,0.1); display:inline-flex; align-items:center; gap:.4rem; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 24px rgba(37,99,235,0.5); }
        .btn-secondary { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #94a3b8; border-radius: 10px; padding: .625rem 1.5rem; font-weight: 500; transition: all .2s; }
        .btn-secondary:hover { border-color: rgba(37,99,235,0.4); color: #e2e8f0; }
        .btn-danger { background: linear-gradient(135deg, #dc2626, #b91c1c); color: white; border-radius: 10px; padding: .625rem 1.5rem; font-weight: 600; transition: all .2s; }
        .btn-danger:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(220,38,38,0.4); }

        /* ── Inputs ── */
        .input-field { border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: .625rem 1rem; width: 100%; transition: border-color .2s, box-shadow .2s; font-size: .95rem; background: rgba(255,255,255,0.04); color: #e2e8f0; }
        .input-field:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.15); }
        .input-field::placeholder { color: #64748b; }
        textarea.input-field { resize: vertical; min-height: 140px; font-family: inherit; }
        select.input-field { cursor: pointer; }
        select.input-field option { background: #0f172a; color: #e2e8f0; }

        /* ── Badges ── */
        .badge { display:inline-flex; align-items:center; padding:.2rem .65rem; border-radius:99px; font-size:.75rem; font-weight:600; }
        .badge-success { background:rgba(16,185,129,0.15); color:#34d399; border:1px solid rgba(16,185,129,0.25); }
        .badge-error   { background:rgba(239,68,68,0.15); color:#f87171; border:1px solid rgba(239,68,68,0.25); }
        .badge-warning { background:rgba(245,158,11,0.15); color:#fbbf24; border:1px solid rgba(245,158,11,0.25); }

        /* ── Flash messages ── */
        .flash-success { background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.25); color:#34d399; border-radius:10px; padding:.75rem 1.25rem; }
        .flash-error   { background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.25); color:#f87171; border-radius:10px; padding:.75rem 1.25rem; }

        /* ── Quota bar ── */
        .quota-bar { height:6px; border-radius:99px; background:rgba(255,255,255,0.08); overflow:hidden; }
        .quota-fill { height:100%; border-radius:99px; background:linear-gradient(90deg,#3b82f6,#2563eb); transition:width .6s ease; }

        /* ── Translation box ── */
        .translation-box { border:1px solid rgba(255,255,255,0.08); border-radius:14px; padding:1.25rem; background:rgba(255,255,255,0.03); min-height:140px; font-family:'Noto Sans Devanagari',sans-serif; font-size:1.05rem; line-height:1.8; color:#e2e8f0; }

        /* ── Table ── */
        .table-row:hover { background: rgba(37,99,235,0.05); }

        /* ── Animations ── */
        @keyframes fadeInUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
        .fade-in { animation:fadeInUp .35s ease both; }
        @keyframes slideIn { from{opacity:0;transform:translateX(20px)} to{opacity:1;transform:translateX(0)} }
        .slide-in { animation:slideIn .3s ease both; }

        /* ── Chatbot ── */
        #chatbot-btn { position:fixed; bottom:28px; right:28px; z-index:9999; width:58px; height:58px; border-radius:50%; background:linear-gradient(135deg,#2563eb,#1d4ed8); box-shadow:0 8px 32px rgba(37,99,235,0.5); border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:1.6rem; transition:all .3s cubic-bezier(0.34,1.56,0.64,1); }
        #chatbot-btn:hover { transform:scale(1.12); box-shadow:0 12px 40px rgba(37,99,235,0.7); }
        #chatbot-panel { position:fixed; bottom:100px; right:28px; z-index:9998; width:360px; max-height:520px; background:#0f172a; border:1px solid rgba(37,99,235,0.3); border-radius:20px; box-shadow:0 24px 80px rgba(0,0,0,.6); display:flex; flex-direction:column; overflow:hidden; transition:all .3s cubic-bezier(0.34,1.56,0.64,1); transform-origin:bottom right; }
        #chatbot-panel.hidden { opacity:0; transform:scale(0.85); pointer-events:none; }
        .chat-msg-bot { background:rgba(37,99,235,0.12); border:1px solid rgba(37,99,235,0.2); border-radius:14px 14px 14px 4px; padding:.65rem 1rem; color:#93c5fd; font-size:.875rem; max-width:85%; align-self:flex-start; }
        .chat-msg-user { background:rgba(37,99,235,0.9); border-radius:14px 14px 4px 14px; padding:.65rem 1rem; color:white; font-size:.875rem; max-width:85%; align-self:flex-end; }

        /* ── Tour overlay ── */
        #tour-overlay { position:fixed; inset:0; z-index:99999; background:rgba(2,8,23,.95); display:flex; align-items:center; justify-content:center; }
        .tour-card { background:#0f172a; border:1px solid rgba(37,99,235,0.4); border-radius:24px; padding:2.5rem; max-width:480px; width:90%; box-shadow:0 32px 80px rgba(0,0,0,.8); }
        .tour-step-dot { width:8px; height:8px; border-radius:50%; background:rgba(255,255,255,0.15); transition:all .3s; }
        .tour-step-dot.active { background:#3b82f6; width:20px; border-radius:99px; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width:5px; } ::-webkit-scrollbar-track { background:#0b1120; } ::-webkit-scrollbar-thumb { background:#1d4ed8; border-radius:99px; }

        /* ── Light Mode Overrides ── */
        body.light-mode { background: #f8fafc; color: #1e293b; }
        body.light-mode header { background: rgba(248,250,252,0.95); border-bottom: 1px solid #e2e8f0; }
        body.light-mode header h1 { color: #1e293b !important; }
        body.light-mode .stat-card, body.light-mode .card { background: white; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        body.light-mode .sidebar { background: #f1f5f9; border-right: 1px solid #e2e8f0; }
        body.light-mode .nav-link { color: #475569; }
        body.light-mode .nav-link:hover { background: #e2e8f0; color: #1e293b; }
        body.light-mode .nav-link.active { background: #dbeafe; color: #1d4ed8; }
        body.light-mode .input-field { background: white; border-color: #cbd5e1; color: #1e293b; }
        body.light-mode .input-field::placeholder { color: #94a3b8; }
        body.light-mode .translation-box { background: #f8fafc; border-color: #cbd5e1; color: #1e293b; }
        body.light-mode .text-white,
        body.light-mode .text-slate-50,
        body.light-mode .text-slate-100,
        body.light-mode .text-slate-200,
        body.light-mode .text-slate-300 { color: #0f172a !important; }
        
        body.light-mode .text-slate-400 { color: #475569 !important; }
        body.light-mode .text-slate-500 { color: #64748b !important; }
        body.light-mode .text-slate-600 { color: #334155 !important; }
        
        body.light-mode .text-gray-400,
        body.light-mode .text-gray-500,
        body.light-mode .text-gray-600 { color: #475569 !important; }
        
        body.light-mode .text-gray-700,
        body.light-mode .text-gray-800,
        body.light-mode .text-gray-900 { color: #0f172a !important; }
        body.light-mode .badge-success { color: #065f46 !important; background: #d1fae5 !important; border-color: #a7f3d0 !important; }
        body.light-mode .badge-error { color: #991b1b !important; background: #fee2e2 !important; border-color: #fecaca !important; }
        body.light-mode .badge-warning { color: #92400e !important; background: #fef3c7 !important; border-color: #fde68a !important; }
        body.light-mode .bg-blue-900\/20 { background: rgba(37,99,235,0.05); }
        body.light-mode .border-blue-900\/30 { border-color: #e2e8f0; }
        body.light-mode .sidebar .text-white { color: #0f172a !important; }
        body.light-mode .text-blue-400 { color: #1d4ed8 !important; }
        .logo-icon { color: white !important; }
    </style>
</head>
<body class="h-full font-sans antialiased">
<div class="flex h-screen overflow-hidden">

    {{-- ── Sidebar ────────────────────────────── --}}
    <aside class="sidebar w-60 flex-shrink-0 flex flex-col" id="sidebar">
        {{-- Logo --}}
        <a href="{{ route('dashboard') }}" class="px-5 py-5 flex items-center gap-3 border-b border-blue-900/30 hover:bg-white/5 transition-colors group">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-blue-900 flex items-center justify-center text-lg shadow-lg group-hover:scale-110 transition-transform logo-icon">🇮🇳</div>
            <div>
                <div class="font-bold text-white leading-tight">JanBhasha</div>
                <div class="text-xs text-blue-400" style="font-family:'Noto Sans Devanagari',sans-serif;">जनभाषा</div>
            </div>
        </a>

        {{-- Org badge --}}
        @auth
        @if(auth()->user()->organisation)
        <div class="mx-3 mt-4 mb-1 bg-blue-900/20 border border-blue-800/30 rounded-xl px-4 py-2.5">
            <div class="text-xs text-blue-500 uppercase tracking-wide font-medium mb-0.5">Organisation</div>
            <div class="font-semibold text-sm text-slate-200 leading-tight truncate">{{ auth()->user()->organisation->name }}</div>
        </div>
        @endif
        @endauth

        {{-- Nav --}}
<<<<<<< HEAD
        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 text-sm">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="{{ route('translations.create') }}" class="nav-link {{ request()->routeIs('translations.create') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 text-sm">
                <i class="fas fa-pen"></i> Translate
            </a>
            <a href="{{ route('translations.index') }}" class="nav-link {{ request()->routeIs('translations.index') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 text-sm">
                <i class="fas fa-clipboard"></i> History
            </a>
            <a href="{{ route('glossary.index') }}" class="nav-link {{ request()->routeIs('glossary.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 text-sm">
                <i class="fas fa-book"></i> Glossary
=======
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto" id="main-nav">
            <a href="{{ route('dashboard') }}" id="nav-dashboard" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 text-sm">
                <span>🏠</span> Dashboard
            </a>
            <a href="{{ route('translations.create') }}" id="nav-translate" class="nav-link {{ request()->routeIs('translations.create') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 text-sm">
                <span>✍️</span> Translate
            </a>
            <a href="{{ route('translations.index') }}" id="nav-history" class="nav-link {{ request()->routeIs('translations.index') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 text-sm">
                <span>📋</span> History
            </a>
            <a href="{{ route('glossary.index') }}" id="nav-glossary" class="nav-link {{ request()->routeIs('glossary.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 text-sm">
                <span>📖</span> Glossary
>>>>>>> upstream/master
            </a>

            @auth
            @if(auth()->user()->isSuperAdmin())
<<<<<<< HEAD
            <div class="pt-3 pb-1 px-4 text-xs text-blue-300 uppercase tracking-wide font-medium"><i class="fas fa-bolt"></i> Admin</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 text-sm">
                <i class="fas fa-sliders-h"></i> Admin Dashboard
            </a>
            <div class="pt-4 pb-1 px-4 text-xs text-blue-500 uppercase tracking-wide font-semibold">Admin</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 text-sm">
                <span>🎛️</span> Admin Panel
            </a>
            <a href="{{ route('admin.organisations.index') }}" class="nav-link {{ request()->routeIs('admin.organisations.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 text-sm">
                <span>🏛️</span> Organisations
            </a>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 text-sm">
                <span>👥</span> Users
        {{-- User footer --}}
        @auth
        <div class="border-t border-blue-900/30 px-3 py-3">
            <div class="flex items-center gap-3 px-2">
                <a href="{{ route('profile.edit') }}" class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white font-bold text-sm shrink-0 hover:ring-2 hover:ring-orange-400 transition-all" title="Profile">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </a>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-slate-200 truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Logout" class="text-slate-500 hover:text-red-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </aside>

    {{-- ── Main ─────────────────────────── --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="px-8 py-4 flex items-center justify-between flex-shrink-0">
            <div>
                <h1 class="text-xl font-bold text-white">{{ $header ?? 'Dashboard' }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">{{ now()->format('l, d F Y') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="toggleTheme()" class="w-10 h-10 rounded-xl bg-blue-900/20 border border-blue-800/30 flex items-center justify-center hover:border-blue-500 transition-all group" title="Switch Mode">
                    <span id="theme-icon" class="text-lg group-hover:scale-110 transition-transform">🌓</span>
                </button>
                <a href="{{ route('translations.create') }}" class="btn-primary text-sm" id="header-new-btn">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    New Translation
                </a>
            </div>
        </header>

        {{-- Flash messages --}}
        <div class="px-8 pt-2">
            @if(session('success'))
            <div class="flash-success mb-2 flex items-center gap-2 fade-in"><span>✅</span> {{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="flash-error mb-2 flex items-center gap-2 fade-in"><span>❌</span> {{ session('error') }}</div>
            @endif
        </div>

        <main class="flex-1 overflow-y-auto px-8 py-6">
            {{ $slot }}
        </main>
    </div>
</div>

{{-- ── Floating Support Chatbot ── --}}
<button id="chatbot-btn" title="Support & Help" onclick="toggleChat()">💬</button>
<div id="chatbot-panel" class="hidden">
    <div class="px-5 py-4 border-b border-blue-900/40 flex items-center justify-between bg-blue-950/40">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-sm">🤖</div>
            <div>
                <div class="text-sm font-semibold text-white">JanBhasha Assistant</div>
                <div class="text-xs text-emerald-400 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span> Online
                </div>
            </div>
        </div>
        <button onclick="toggleChat()" class="text-slate-500 hover:text-slate-300 transition-colors">✕</button>
    </div>
    <div id="chat-messages" class="flex-1 overflow-y-auto p-4 flex flex-col gap-3" style="min-height:280px;max-height:340px;">
        <div class="chat-msg-bot">👋 Hi! I'm your JanBhasha guide. How can I help you today?</div>
        <div class="flex flex-wrap gap-2 mt-1">
            <button onclick="askBot('how to translate')" class="text-xs bg-blue-900/40 border border-blue-800/40 text-blue-300 rounded-full px-3 py-1 hover:bg-blue-800/40 transition-colors">How to translate?</button>
            <button onclick="askBot('what is glossary')" class="text-xs bg-blue-900/40 border border-blue-800/40 text-blue-300 rounded-full px-3 py-1 hover:bg-blue-800/40 transition-colors">What is Glossary?</button>
            <button onclick="askBot('api key')" class="text-xs bg-blue-900/40 border border-blue-800/40 text-blue-300 rounded-full px-3 py-1 hover:bg-blue-800/40 transition-colors">API Key?</button>
            <button onclick="askBot('quota')" class="text-xs bg-blue-900/40 border border-blue-800/40 text-blue-300 rounded-full px-3 py-1 hover:bg-blue-800/40 transition-colors">About Quota?</button>
        </div>
    </div>
    <div class="p-3 border-t border-blue-900/40">
        <form onsubmit="sendChat(event)" class="flex gap-2">
            <input id="chat-input" type="text" placeholder="Ask a question..." class="flex-1 bg-blue-900/20 border border-blue-800/30 rounded-xl px-4 py-2 text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-blue-500">
            <button type="submit" class="w-9 h-9 rounded-xl bg-blue-600 hover:bg-blue-500 flex items-center justify-center text-white text-sm transition-colors">→</button>
        </form>
    </div>
</div>

{{-- ── Website Tour Overlay ── --}}
@auth
@if(!auth()->user()->tour_completed)
<div id="tour-overlay">
    <div class="tour-card text-center">
        <div id="tour-content">
            <!-- Steps populated by JS -->
        </div>
        <div class="flex justify-center gap-2 mt-6 mb-5" id="tour-dots"></div>
        <div class="flex gap-3 justify-center">
            <button onclick="skipTour()" class="btn-secondary text-sm">Skip Tour</button>
            <button onclick="nextStep()" id="tour-next-btn" class="btn-primary text-sm">Next →</button>
        </div>
    </div>
</div>
@endif
@endauth

<script src="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.js.umd.js"></script>
<script>
// ── Chatbot ──
let chatOpen = false;
const botPanel = document.getElementById('chatbot-panel');

function toggleChat() {
    chatOpen = !chatOpen;
    botPanel.classList.toggle('hidden', !chatOpen);
}

const botAnswers = {
    'how to translate': 'Click <b>✍️ Translate</b> in the sidebar, paste your English text, and hit Submit. The result appears instantly in Hindi!',
    'what is glossary': 'The <b>📖 Glossary</b> lets you add custom term mappings (e.g. "Ministry" → "मंत्रालय") that are always preserved during translation.',
    'api key': 'Your API key is in the <b>Admin → Organisations</b> panel. Use it in the <code>X-API-Key</code> header to access the REST API.',
    'quota': 'Each organisation has a monthly character quota. Track your usage on the <b>🏠 Dashboard</b>. Contact your admin to increase limits.',
};

function addMessage(text, isUser = false) {
    const msgs = document.getElementById('chat-messages');
    const div = document.createElement('div');
    div.className = isUser ? 'chat-msg-user slide-in' : 'chat-msg-bot slide-in';
    div.innerHTML = text;
    msgs.appendChild(div);
    msgs.scrollTop = msgs.scrollHeight;
}

function askBot(topic) {
    addMessage(topic, true);
    setTimeout(() => addMessage(botAnswers[topic] || "I'm not sure about that. Please contact your administrator for help."), 400);
}

function sendChat(e) {
    e.preventDefault();
    const input = document.getElementById('chat-input');
    const text = input.value.trim();
    if (!text) return;
    addMessage(text, true);
    input.value = '';
    const lower = text.toLowerCase();
    let reply = "I'm not sure about that. Please try one of the quick options above, or contact your administrator.";
    if (lower.includes('translat')) reply = botAnswers['how to translate'];
    else if (lower.includes('glossary') || lower.includes('term')) reply = botAnswers['what is glossary'];
    else if (lower.includes('api') || lower.includes('key')) reply = botAnswers['api key'];
    else if (lower.includes('quota') || lower.includes('limit')) reply = botAnswers['quota'];
    setTimeout(() => addMessage(reply), 450);
}

// ── Tour System ──
const steps = [
    { icon:'🎉', title:'Welcome to JanBhasha!', body:'Your government translation portal is ready. Let us show you around in just a few steps.' },
    { icon:'🏠', title:'Dashboard', body:'Your dashboard shows translation stats, quota usage, and recent activity at a glance.' },
    { icon:'✍️', title:'Translate', body:'Click <b>Translate</b> in the sidebar to submit English text and receive a Hindi translation instantly.' },
    { icon:'📖', title:'Glossary', body:'Add custom term mappings to ensure domain-specific words are always translated correctly.' },
    { icon:'📋', title:'History', body:'Every translation is logged. Browse, search, and copy any past translation from the History page.' },
    { icon:'🚀', title:"You're all set!", body:"Start by clicking <b>✍️ Translate</b> in the sidebar. You can revisit this tour from the Help chatbot anytime." },
];

let currentStep = 0;

function renderTour() {
    const overlay = document.getElementById('tour-overlay');
    if (!overlay) return;
    const content = document.getElementById('tour-content');
    const dots = document.getElementById('tour-dots');
    const nextBtn = document.getElementById('tour-next-btn');
    const s = steps[currentStep];
    content.innerHTML = `<div class="text-5xl mb-4">${s.icon}</div><h2 class="text-xl font-bold text-white mb-3">${s.title}</h2><p class="text-slate-400 text-sm leading-relaxed">${s.body}</p>`;
    dots.innerHTML = steps.map((_, i) => `<div class="tour-step-dot ${i===currentStep?'active':''}"></div>`).join('');
    nextBtn.textContent = currentStep === steps.length - 1 ? "Get Started!" : "Next →";
}

function nextStep() {
    if (currentStep < steps.length - 1) { currentStep++; renderTour(); }
    else completeTour();
}

function skipTour() { completeTour(); }

function completeTour() {
    const overlay = document.getElementById('tour-overlay');
    if (overlay) overlay.remove();
    fetch('/tour/complete', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json' },
    });
}

if (document.getElementById('tour-overlay')) renderTour();

// ── Theme System ──
function toggleTheme() {
    const body = document.body;
    const icon = document.getElementById('theme-icon');
    const isLight = body.classList.toggle('light-mode');
    localStorage.setItem('theme', isLight ? 'light' : 'dark');
    if (icon) icon.textContent = isLight ? '☀️' : '🌓';
}

// Initialize Theme
(function() {
    if (localStorage.getItem('theme') === 'light') {
        document.body.classList.add('light-mode');
        // Icon update is handled after DOM load in the toggle button itself if needed,
        // but since it's hardcoded to 🌓, we'll fix it on load.
        document.addEventListener('DOMContentLoaded', () => {
            const icon = document.getElementById('theme-icon');
            if (icon) icon.textContent = '☀️';
        });
    }
})();
</script>
</body>
</html>
