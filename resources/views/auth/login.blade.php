<x-guest-layout>
    <h2 class="text-xl font-bold text-gray-800 mb-1">Welcome back</h2>
    <p class="text-sm text-gray-500 mb-6">Sign in to your organisation account</p>

    <!-- Session Status -->
    @if(session('status'))
    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-3">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="input-field" placeholder="you@organisation.gov.in">
            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:underline">Forgot password?</a>
                @endif
            </div>
            <input id="password" type="password" name="password" required
                   class="input-field" placeholder="••••••••">
            @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-2">
            <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-blue-600">
            <label for="remember_me" class="text-sm text-gray-600">Remember me</label>
        </div>

        <button type="submit" class="btn-primary mt-2">Sign In →</button>
    </form>

    <div class="mt-6 pt-5 border-t border-gray-100 text-center">
        <p class="text-xs text-gray-500 mb-4">Demo credentials: <span class="font-mono text-blue-700">finance@janbhasha.in</span> / <span class="font-mono text-blue-700">password</span></p>
        
        <p class="text-sm text-gray-600">
            Don't have an account? 
            <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                Create an account
            </a>
        </p>
    </div>
</x-guest-layout>
