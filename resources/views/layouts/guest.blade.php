<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'JanBhasha') }} — {{ $title ?? 'Sign In' }}</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Sans+Devanagari:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #020817; color: #e2e8f0; min-height: 100vh; }
        .grid-overlay {
            background-image: linear-gradient(rgba(37,99,235,0.05) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(37,99,235,0.05) 1px, transparent 1px);
            background-size: 50px 50px;
        }
        .hero-glow {
            background: radial-gradient(ellipse 70% 50% at 50% 0%, rgba(37,99,235,0.25) 0%, transparent 70%);
        }
        .glass-card {
            background: rgba(15,23,42,0.9);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(37,99,235,0.2);
            border-radius: 24px;
            box-shadow: 0 32px 80px rgba(0,0,0,.6), inset 0 1px 0 rgba(255,255,255,0.05);
        }
        .input-field {
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: .7rem 1rem;
            width: 100%;
            transition: border-color .2s, box-shadow .2s;
            font-size: .95rem;
            background: rgba(255,255,255,0.04);
            color: #e2e8f0;
        }
        .input-field:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.15); }
        .input-field::placeholder { color: #475569; }
        select.input-field option { background: #0f172a; color: #e2e8f0; }
        label { color: #94a3b8; font-size: .875rem; font-weight: 500; }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white; border-radius: 10px; padding: .8rem 1.5rem;
            font-weight: 700; width: 100%;
            transition: all .25s cubic-bezier(0.34,1.56,0.64,1);
            box-shadow: 0 4px 20px rgba(37,99,235,0.4), inset 0 1px 0 rgba(255,255,255,0.1);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(37,99,235,0.6); }

        .tricolor { height: 3px; background: linear-gradient(90deg, #FF9933 33.33%, white 33.33% 66.66%, #138808 66.66%); }

        @keyframes fadeInUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .fade-in { animation: fadeInUp .5s ease both; }

        .gradient-text {
            background: linear-gradient(135deg, #60a5fa, #ffffff 50%, #fb923c);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #020817; }
        ::-webkit-scrollbar-thumb { background: #1d4ed8; border-radius: 99px; }
        /* ── Light Mode Overrides ── */
        body.light-mode { background: #f8fafc; color: #1e293b; }
        body.light-mode .hero-glow { background: radial-gradient(ellipse 70% 50% at 50% 0%, rgba(37,99,235,0.1) 0%, transparent 70%); }
        body.light-mode .glass-card { background: white; border-color: #e2e8f0; box-shadow: 0 8px 32px rgba(0,0,0,0.05); }
        body.light-mode .input-field { background: white; border-color: #cbd5e1; color: #1e293b; }
        body.light-mode .input-field::placeholder { color: #94a3b8; }
        body.light-mode .text-slate-500 { color: #475569 !important; }
        body.light-mode .text-slate-600 { color: #64748b !important; }
        body.light-mode .gradient-text { background: linear-gradient(135deg, #1d4ed8, #0f172a); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    <div class="grid-overlay fixed inset-0 pointer-events-none"></div>
    <div class="hero-glow fixed inset-0 pointer-events-none"></div>
    <div class="tricolor fixed top-0 left-0 right-0 z-50"></div>

    <!-- Top Bar -->
    <div class="fixed top-5 left-6 right-6 z-40 flex items-center justify-between">
        <a href="{{ url('/') }}" class="flex items-center gap-2 text-slate-500 hover:text-blue-400 text-sm transition-colors">
            ← Home
        </a>
        <button onclick="toggleTheme()" class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center hover:border-blue-500 transition-all group" title="Switch Mode">
            <span id="theme-icon" class="text-lg group-hover:scale-110 transition-transform">🌓</span>
        </button>
    </div>

    <div class="flex-1 flex items-center justify-center px-4 py-16 relative z-10">
        <div class="w-full max-w-md fade-in">

            {{-- Logo --}}
            <div class="text-center mb-8">
<<<<<<< HEAD
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-700 text-white text-3xl shadow-lg mb-4"><i class="fas fa-globe"></i></div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">JanBhasha</h1>
                <p class="text-gray-500 mt-1 text-sm font-medium" style="font-family:'Noto Sans Devanagari',sans-serif;">जनभाषा — सरकारी अनुवाद पोर्टल</p>
                <p class="text-gray-400 text-xs mt-1">Government Translation Portal</p>
=======
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4 overflow-hidden shadow-lg shadow-blue-900/50 border border-blue-800/30 hover:scale-105 transition-transform group">
                    <img src="/favicon.png" alt="JanBhasha" class="w-full h-full object-cover">
                </a>
                <h1 class="text-3xl font-extrabold gradient-text tracking-tight">JanBhasha</h1>
                <p class="text-slate-500 mt-1 text-sm" style="font-family:'Noto Sans Devanagari',sans-serif;">जनभाषा — सरकारी अनुवाद पोर्टल</p>
>>>>>>> upstream/master
            </div>

            {{-- Card --}}
            <div class="glass-card p-8">
                {{ $slot }}
            </div>

            <p class="text-center text-xs text-slate-600 mt-6">
                © {{ date('Y') }} JanBhasha · Developed for Indian Government Organisations
            </p>
        </div>
    </div>

    <div class="tricolor fixed bottom-0 left-0 right-0 z-50"></div>
    <script>
        function toggleTheme() {
            const body = document.body;
            const icon = document.getElementById('theme-icon');
            const isLight = body.classList.toggle('light-mode');
            localStorage.setItem('theme', isLight ? 'light' : 'dark');
            if (icon) icon.textContent = isLight ? '☀️' : '🌓';
        }

        (function() {
            if (localStorage.getItem('theme') === 'light') {
                document.body.classList.add('light-mode');
                document.addEventListener('DOMContentLoaded', () => {
                    const icon = document.getElementById('theme-icon');
                    if (icon) icon.textContent = '☀️';
                });
            }
        })();
    </script>
</body>
</html>
