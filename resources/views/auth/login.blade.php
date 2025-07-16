<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Booking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-r from-blue-100 via-blue-100 to-blue-100 flex items-center justify-center">
    <div class="flex bg-white rounded-2xl shadow-xl overflow-hidden w-full max-w-4xl">
        
        <!-- Left Image / Icon Branding Panel -->
        <div class="hidden md:flex w-1/2 relative bg-[url('https://source.unsplash.com/featured/?books')] bg-cover bg-center items-center justify-center">
            <!-- Top Branding Text -->
            <div class="absolute top-6 left-6 text-black text-2xl font-bold drop-shadow-lg">
                📘 BookingApp
            </div>
            <!-- Center Icon -->
            <div class="bg-white bg-opacity-80 p-6 rounded-full shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 16h8M8 12h8m-7 8h6a2 2 0 002-2V6a2 2 0 00-2-2h-6a2 2 0 00-2 2v16z"/>
                </svg>
            </div>
        </div>

        <!-- Login Form -->
        <div class="w-full md:w-1/2 p-8 sm:p-12">
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold text-gray-800">Welcome Back</h1>
                <p class="text-sm text-gray-500">Login to your Booking System account</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">📧</span>
                        <input id="email" name="email" type="email" required
                               class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               placeholder="you@example.com" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">🔒</span>
                        <input id="password" name="password" type="password" required
                               class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               placeholder="••••••••" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between mb-4">
                    <label class="flex items-center text-sm">
                        <input type="checkbox" name="remember" class="mr-2 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" />
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:underline">Forgot password?</a>
                    @endif
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg font-semibold transition duration-200">
                        Log In
                    </button>
                </div>

                <!-- Register -->
                <div class="text-center mt-6">
                    <p class="text-sm text-gray-700">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-indigo-600 hover:underline font-medium">Register here</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>