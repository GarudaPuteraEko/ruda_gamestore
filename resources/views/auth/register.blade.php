<x-guest-layout>
    <div class="p-6">
        <div class="flex justify-center mb-6">
            <a href="{{ url('/') }}" class="flex items-center text-2xl font-bold text-blue-800 hover:text-blue-900 transition-colors">
                <img src="{{ asset('images/game-store-logo-design-template-260nw-2465937507-removebg-preview.png') }}" alt="Ruda Gamestore Logo" class="h-8 w-15 mr-2">
                Ruda Gamestore
            </a>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" class="text-blue-700 font-medium" />
                <x-text-input id="name" class="block mt-1 w-full px-4 py-2 border border-blue-400 rounded-lg focus:ring-blue-600 focus:border-blue-600 bg-blue-200 text-blue-900" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500 text-sm" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-blue-700 font-medium" />
                <x-text-input id="email" class="block mt-1 w-full px-4 py-2 border border-blue-400 rounded-lg focus:ring-blue-600 focus:border-blue-600 bg-blue-200 text-blue-900" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" class="text-blue-700 font-medium" />
                <x-text-input id="password" class="block mt-1 w-full px-4 py-2 border border-blue-400 rounded-lg focus:ring-blue-600 focus:border-blue-600 bg-blue-200 text-blue-900"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-blue-700 font-medium" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full px-4 py-2 border border-blue-400 rounded-lg focus:ring-blue-600 focus:border-blue-600 bg-blue-200 text-blue-900"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-sm" />
            </div>

            <div class="flex items-center justify-between">
                <a class="text-sm text-blue-600 hover:text-blue-800 underline transition duration-200" href="{{ route('login') }}">
                    {{ __('Already registered? Login here') }}
                </a>

                <x-primary-button class="px-6 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-900 transition duration-200 shadow-md">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>