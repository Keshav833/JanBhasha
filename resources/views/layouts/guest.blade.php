<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'JanBhasha') }} — {{ $title ?? 'Sign In' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Sans+Devanagari:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .input-field { border: 1.5px solid #e2e8f0; border-radius: 10px; padding: .625rem 1rem; width: 100%; transition: border-color .2s, box-shadow .2s; font-size: .95rem; background: white; }
        .input-field:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.1); }
        .btn-primary { background: linear-gradient(135deg, #1d4ed8, #2563eb); color: white; border-radius: 10px; padding: .75rem 1.5rem; font-weight: 600; width: 100%; transition: all .2s; box-shadow: 0 2px 8px rgba(37,99,235,.3); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(37,99,235,.4); }
        .tricolor-bar { height: 4px; background: linear-gradient(90deg, #FF9933 33.33%, white 33.33% 66.66%, #138808 66.66%); }
    </style>
</head>
<body class="min-h-screen flex flex-col" style="background: linear-gradient(135deg, #eff6ff 0%, #f0fdf4 50%, #fff7ed 100%);">

    <div class="tricolor-bar w-full"></div>

    <div class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">

            {{-- Logo / branding --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-700 text-white text-3xl shadow-lg mb-4"><i class="fas fa-globe"></i></div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">JanBhasha</h1>
                <p class="text-gray-500 mt-1 text-sm font-medium" style="font-family:'Noto Sans Devanagari',sans-serif;">जनभाषा — सरकारी अनुवाद पोर्टल</p>
                <p class="text-gray-400 text-xs mt-1">Government Translation Portal</p>
            </div>

            {{-- Card --}}
            <div class="bg-white rounded-2xl shadow-xl shadow-blue-100/60 p-8">
                {{ $slot }}
            </div>

            <p class="text-center text-xs text-gray-400 mt-6">
                © {{ date('Y') }} JanBhasha. Developed for Indian Government Organisations.
            </p>
        </div>
    </div>
    <div class="tricolor-bar w-full"></div>
</body>
</html>
