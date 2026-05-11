<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JanBhasha — Government Translation Portal</title>
    <meta name="description" content="JanBhasha is India's premier AI-powered government translation platform. Translate official documents from English to Hindi instantly.">
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Noto+Sans+Devanagari:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #020817; color: #e2e8f0; overflow-x: hidden; }

        /* ── Animated gradient background ── */
        .hero-bg {
            background: radial-gradient(ellipse 80% 50% at 50% -20%, rgba(37,99,235,0.35) 0%, transparent 70%),
                        radial-gradient(ellipse 60% 40% at 80% 80%, rgba(249,115,22,0.15) 0%, transparent 60%),
                        radial-gradient(ellipse 50% 40% at 10% 90%, rgba(16,185,129,0.1) 0%, transparent 60%),
                        #020817;
        }
        .grid-overlay {
            background-image: linear-gradient(rgba(37,99,235,0.06) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(37,99,235,0.06) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* ── Glass cards ── */
        .glass { background: rgba(255,255,255,0.04); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.08); }
        .glass-hover:hover { background: rgba(255,255,255,0.07); border-color: rgba(37,99,235,0.4); transform: translateY(-4px); box-shadow: 0 20px 60px rgba(37,99,235,0.15); }

        /* ── Glowing elements ── */
        .glow-blue { box-shadow: 0 0 40px rgba(37,99,235,0.4); }
        .glow-orange { box-shadow: 0 0 40px rgba(249,115,22,0.3); }
        .text-glow { text-shadow: 0 0 40px rgba(96,165,250,0.5); }

        /* ── Gradient text ── */
        .gradient-text {
            background: linear-gradient(135deg, #60a5fa 0%, #ffffff 40%, #fb923c 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        /* ── Buttons ── */
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white; border-radius: 12px; padding: .875rem 2rem; font-weight: 700;
            transition: all .3s cubic-bezier(0.34,1.56,0.64,1);
            box-shadow: 0 4px 20px rgba(37,99,235,0.4), inset 0 1px 0 rgba(255,255,255,0.1);
        }
        .btn-primary:hover { transform: translateY(-2px) scale(1.02); box-shadow: 0 8px 30px rgba(37,99,235,0.6); }
        .btn-outline {
            background: transparent; color: #93c5fd;
            border: 1.5px solid rgba(147,197,253,0.4); border-radius: 12px; padding: .875rem 2rem; font-weight: 600;
            transition: all .25s;
        }
        .btn-outline:hover { background: rgba(37,99,235,0.15); border-color: #60a5fa; color: white; }

        /* ── Tricolor bar ── */
        .tricolor { height: 3px; background: linear-gradient(90deg, #FF9933 33.33%, white 33.33% 66.66%, #138808 66.66%); }

        /* ── Floating animation ── */
        @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        .floating { animation: float 4s ease-in-out infinite; }

        /* ── Fade in up ── */
        @keyframes fadeInUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
        .anim-1 { animation: fadeInUp .6s ease both; }
        .anim-2 { animation: fadeInUp .6s .15s ease both; }
        .anim-3 { animation: fadeInUp .6s .3s ease both; }
        .anim-4 { animation: fadeInUp .6s .45s ease both; }

        /* ── Stat counter ── */
        .stat-number { background: linear-gradient(135deg, #60a5fa, #34d399); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

        /* ── Feature icon ── */
        .feature-icon { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; } ::-webkit-scrollbar-track { background: #020817; } ::-webkit-scrollbar-thumb { background: #1d4ed8; border-radius: 99px; }

        /* ── Pulse dot ── */
        @keyframes pulse-dot { 0%,100% { transform: scale(1); opacity:1; } 50% { transform: scale(1.5); opacity:.5; } }
        .pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }

        /* ── Orbit ring ── */
        @keyframes orbit { from { transform: rotate(0deg) translateX(90px) rotate(0deg); } to { transform: rotate(360deg) translateX(90px) rotate(-360deg); } }
        .orbit-item { animation: orbit 8s linear infinite; position: absolute; }
        .orbit-item:nth-child(2) { animation-delay: -2.67s; }
        .orbit-item:nth-child(3) { animation-delay: -5.33s; }
        /* ── Light Mode Overrides ── */
        body.light-mode { background: #f8fafc; color: #1e293b; }
        body.light-mode .hero-bg { background: #f8fafc; }
        body.light-mode .glass { background: white; border-color: #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        body.light-mode .text-white { color: #0f172a !important; }
        body.light-mode .text-slate-200 { color: #1e293b !important; }
        body.light-mode .text-slate-300 { color: #334155 !important; }
        body.light-mode .text-slate-400 { color: #475569 !important; }
        body.light-mode .text-slate-500 { color: #64748b !important; }
        body.light-mode .gradient-text { background: linear-gradient(135deg, #1d4ed8, #0f172a); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        body.light-mode .text-blue-400 { color: #1d4ed8 !important; }
        .logo-icon { color: white !important; }
    </style>
</head>
<body class="hero-bg">
    <div class="grid-overlay fixed inset-0 pointer-events-none"></div>

    <!-- ── Tricolor top bar ── -->
    <div class="tricolor fixed top-0 left-0 right-0 z-50"></div>

    <!-- ── Navbar ── -->
    <nav class="fixed top-3 left-0 right-0 z-40 px-6">
        <div class="max-w-7xl mx-auto glass rounded-2xl px-6 py-3.5 flex items-center justify-between">
            <a href="{{ auth()->check() ? route('dashboard') : '/' }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center text-lg shadow-lg group-hover:scale-110 transition-transform logo-icon">🇮🇳</div>
                <div>
                    <span class="font-bold text-white">JanBhasha</span>
                    <span class="text-blue-400 text-xs ml-2 hidden sm:inline" style="font-family:'Noto Sans Devanagari',sans-serif;">जनभाषा</span>
                </div>
            </a>
            <div class="flex items-center gap-3">
                <button onclick="toggleTheme()" class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center hover:border-blue-500 transition-all group" title="Switch Mode">
                    <span id="theme-icon" class="text-lg group-hover:scale-110 transition-transform">🌓</span>
                </button>
                <a href="{{ route('login') }}" class="btn-outline text-sm py-2 px-5">Sign In</a>
                <a href="{{ route('register') }}" class="btn-primary text-sm py-2 px-5">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- ── Hero Section ── -->
    <section class="relative min-h-screen flex items-center justify-center pt-24 pb-16 px-6">
        <div class="max-w-5xl mx-auto text-center">

            <!-- Badge -->
            <div class="inline-flex items-center gap-2 glass rounded-full px-5 py-2 text-sm text-blue-300 mb-8 anim-1">
                <span class="w-2 h-2 rounded-full bg-emerald-400 pulse-dot"></span>
                Official Government Translation Platform · भारत
            </div>

            <!-- Headline -->
            <h1 class="text-5xl sm:text-7xl font-black leading-[1.08] tracking-tight mb-6 anim-2">
                <span class="gradient-text text-glow">Bridge the Language</span><br>
                <span class="text-white">Gap in Governance</span>
            </h1>

            <!-- Subheadline -->
            <p class="text-lg sm:text-xl text-slate-400 max-w-2xl mx-auto mb-10 anim-3" style="line-height:1.7;">
                JanBhasha empowers Indian government organisations to translate official documents from English to Hindi with AI-grade accuracy, glossary protection, and complete audit trails.
            </p>

            <!-- CTAs -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16 anim-4">
                <a href="{{ route('register') }}" class="btn-primary text-base">
                    Start Translating Free →
                </a>
                <a href="{{ route('login') }}" class="btn-outline text-base">
                    I have an account
                </a>
            </div>

            <!-- Translation Demo Card -->
            <div class="glass rounded-2xl p-6 max-w-3xl mx-auto text-left anim-4">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    <span class="text-slate-500 text-xs ml-2">Live Translation Preview</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-slate-900/60 rounded-xl p-4 border border-slate-700/50">
                        <div class="text-xs text-slate-500 uppercase tracking-wide mb-2 flex items-center gap-1.5">🇬🇧 English Source</div>
                        <p class="text-slate-300 text-sm leading-relaxed">The Ministry of Finance hereby announces the allocation of funds for the upcoming fiscal year under the PM Awas Yojana scheme.</p>
                    </div>
                    <div class="bg-blue-950/40 rounded-xl p-4 border border-blue-800/30">
                        <div class="text-xs text-blue-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">🇮🇳 Hindi Translation</div>
                        <p class="text-slate-200 text-sm leading-relaxed" style="font-family:'Noto Sans Devanagari',sans-serif;">वित्त मंत्रालय इसके द्वारा पीएम आवास योजना के तहत आगामी वित्तीय वर्ष के लिए धन आवंटन की घोषणा करता है।</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 mt-4 pt-4 border-t border-slate-700/50">
                    <span class="text-emerald-400 text-xs flex items-center gap-1">✅ Glossary protected</span>
                    <span class="text-blue-400 text-xs flex items-center gap-1">⚡ 0.3s response</span>
                    <span class="text-slate-400 text-xs flex items-center gap-1 ml-auto">Provider: AI Engine</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ── Stats ── -->
    <section class="py-20 px-6">
        <div class="max-w-5xl mx-auto grid grid-cols-2 sm:grid-cols-4 gap-6">
            @foreach([['50+', 'Government Depts.'], ['2M+', 'Words Translated'], ['99.9%', 'Uptime SLA'], ['< 1s', 'Avg. Response']] as $stat)
            <div class="glass glass-hover rounded-2xl p-6 text-center transition-all duration-300 cursor-default">
                <div class="text-3xl font-black stat-number mb-1">{{ $stat[0] }}</div>
                <div class="text-slate-400 text-sm">{{ $stat[1] }}</div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- ── Features ── -->
    <section class="py-20 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-4xl font-bold text-white mb-4">Built for Modern Governance</h2>
                <p class="text-slate-400 text-lg max-w-xl mx-auto">Every feature designed around the needs of Indian government departments.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    ['🛡️', 'Glossary Protection', 'Domain-specific terms are tokenised before translation so ministry-specific terminology is always preserved accurately.', 'from-blue-600/20 to-blue-800/10', 'border-blue-800/30'],
                    ['📊', 'Usage Quota Tracking', 'Per-organisation monthly character quotas with real-time dashboards, alerts, and complete usage history.', 'from-orange-600/20 to-orange-800/10', 'border-orange-800/30'],
                    ['🔑', 'Secure API Access', 'REST API with X-API-Key authentication. Integrate translations into any government portal or mobile app.', 'from-emerald-600/20 to-emerald-800/10', 'border-emerald-800/30'],
                    ['📝', 'Full Audit Trail', 'Every translation is logged with timestamp, user, provider, and character count. DPDP-ready compliance.', 'from-purple-600/20 to-purple-800/10', 'border-purple-800/30'],
                    ['🏛️', 'Multi-Department', 'Strict data isolation ensures each organisation only sees their own translations and glossary entries.', 'from-cyan-600/20 to-cyan-800/10', 'border-cyan-800/30'],
                    ['⚡', 'Smart Caching', '24-hour intelligent cache means repeated translations are instant and quota-friendly.', 'from-rose-600/20 to-rose-800/10', 'border-rose-800/30'],
                ] as $f)
                <div class="glass glass-hover rounded-2xl p-6 border {{ $f[4] }} transition-all duration-300 bg-gradient-to-br {{ $f[3] }}">
                    <div class="text-3xl mb-4">{{ $f[0] }}</div>
                    <h3 class="font-semibold text-white text-lg mb-2">{{ $f[1] }}</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">{{ $f[2] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ── CTA Section ── -->
    <section class="py-24 px-6">
        <div class="max-w-3xl mx-auto text-center glass rounded-3xl p-12 glow-blue">
            <div class="text-5xl mb-6">🇮🇳</div>
            <h2 class="text-4xl font-bold text-white mb-4">Ready to digitise your<br>translation workflow?</h2>
            <p class="text-slate-400 mb-8">Join Indian government departments already using JanBhasha to bridge the language gap.</p>
            <a href="{{ route('register') }}" class="btn-primary inline-block text-lg">Create Your Account →</a>
        </div>
    </section>

    <!-- ── Footer ── -->
    <footer class="border-t border-slate-800/60 py-8 px-6 text-center">
        <div class="tricolor mb-6 max-w-xs mx-auto rounded-full"></div>
        <p class="text-slate-500 text-sm">© {{ date('Y') }} JanBhasha — जनभाषा. Developed for Indian Government Organisations.</p>
        <div class="flex justify-center gap-6 mt-4">
            <a href="{{ route('login') }}" class="text-slate-500 hover:text-blue-400 text-sm transition-colors">Sign In</a>
            <a href="{{ route('register') }}" class="text-slate-500 hover:text-blue-400 text-sm transition-colors">Register</a>
        </div>
    </footer>
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
