<x-guest-layout>
    <div class="p-6">
        <div class="flex justify-center mb-6">
            <a href="{{ url('/') }}" class="flex items-center text-2xl font-bold text-blue-800 hover:text-blue-900 transition-colors">
                <img src="{{ asset('images/game-store-logo-design-template-260nw-2465937507-removebg-preview.png') }}" alt="Ruda Gamestore Logo" class="h-8 w-15 mr-2">
                Ruda Gamestore
            </a>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 text-sm text-green-600" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-blue-700 font-medium" />
                <x-text-input id="email" class="block mt-1 w-full px-4 py-2 border border-blue-400 rounded-lg focus:ring-blue-600 focus:border-blue-600 bg-blue-200 text-blue-900" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" class="text-blue-700 font-medium" />
                <x-text-input id="password" class="block mt-1 w-full px-4 py-2 border border-blue-400 rounded-lg focus:ring-blue-600 focus:border-blue-600 bg-blue-200 text-blue-900"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
            </div>

            <!-- Remember Me -->
            <div class="block">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                    <span class="ms-2 text-sm text-gray-700">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-between">
                @if (Route::has('password.request'))
                    <a class="text-sm text-blue-600 hover:text-blue-800 underline transition duration-200" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button class="px-6 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-900 transition duration-200 shadow-md">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>

            <!-- Link ke register -->
            <p class="mt-6 text-center text-sm text-gray-600">
                {{ __("Don't have account?") }}
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-semibold underline">
                    {{ __('Register here') }}
                </a>
            </p>
        </form>
    </div>
</x-guest-layout>
