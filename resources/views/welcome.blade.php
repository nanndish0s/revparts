<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RevParts - Automotive Parts Management</title>
    @vite('resources/css/app.css')
    <style>
        body {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }
    </style>
</head>
<body class="antialiased min-h-screen">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-5xl w-full bg-white/90 backdrop-blur-sm shadow-2xl rounded-3xl overflow-hidden flex flex-col md:flex-row">
            {{-- Left Side: RevParts Branding --}}
            <div class="md:w-1/2 bg-gradient-to-br from-blue-600 to-purple-700 flex items-center justify-center p-12 relative overflow-hidden">
                <div class="text-center z-10">
                    <div class="mb-6">
                        <svg class="mx-auto h-20 w-20 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.5 9.51a4.22 4.22 0 0 1-1.91-1.34A5.77 5.77 0 0 0 12 6a5.74 5.74 0 0 0-4.59 2.17A4.22 4.22 0 0 1 5.5 9.51 4.64 4.64 0 0 0 2 14.12a4.53 4.53 0 0 0 1.65 3.41A4.52 4.52 0 0 0 2 21.09 4.67 4.67 0 0 0 6.5 24a4.56 4.56 0 0 0 3.18-1.28A5.68 5.68 0 0 0 12 22a5.68 5.68 0 0 0 2.32-1.28A4.56 4.56 0 0 0 17.5 24 4.67 4.67 0 0 0 22 19.09a4.52 4.52 0 0 0-1.65-3.41A4.53 4.53 0 0 0 22 14.12a4.64 4.64 0 0 0-3.5-4.61zM12 4.5a3.25 3.25 0 1 1-3.25 3.25A3.25 3.25 0 0 1 12 4.5zM7.5 20.5a2.5 2.5 0 0 1-2.5-2.5 2.52 2.52 0 0 1 .27-1.11 2.52 2.52 0 0 1 .75-.88 2.5 2.5 0 0 1 1.48-.51 2.5 2.5 0 0 1 2.5 2.5 2.5 2.5 0 0 1-2.5 2.5zm9 0a2.5 2.5 0 0 1-2.5-2.5 2.52 2.52 0 0 1 .27-1.11 2.52 2.52 0 0 1 .75-.88 2.5 2.5 0 0 1 1.48-.51 2.5 2.5 0 0 1 2.5 2.5 2.5 2.5 0 0 1-2.5 2.5z"/>
                        </svg>
                    </div>
                    <h1 class="text-5xl font-extrabold text-white tracking-tight mb-4">RevParts</h1>
                    <p class="text-xl text-white/80 max-w-md mx-auto">
                        Streamline Your Automotive Parts Management
                    </p>
                </div>
                
                {{-- Decorative Background Elements --}}
                <div class="absolute top-0 left-0 w-full h-full opacity-20">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500 rounded-full transform rotate-45 -translate-x-1/2 -translate-y-1/2"></div>
                    <div class="absolute bottom-0 left-0 w-80 h-80 bg-purple-500 rounded-full transform -rotate-45 translate-x-1/2 translate-y-1/2"></div>
                </div>
            </div>

            {{-- Right Side: Authentication --}}
            <div class="md:w-1/2 flex flex-col justify-center items-center p-12 space-y-8">
                <div class="text-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Welcome to RevParts</h2>
                    <p class="text-gray-600 max-w-md">
                        Revolutionize your parts inventory with cutting-edge technology
                    </p>
                </div>

                <div class="space-y-6 w-full max-w-md">
                    @auth
                        <a href="{{ route('dashboard') }}" 
                           class="w-full block text-center px-6 py-4 bg-blue-600 text-white font-semibold rounded-xl 
                                  hover:bg-blue-700 transition duration-300 ease-in-out transform 
                                  hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 
                                  focus:ring-blue-500 focus:ring-offset-2">
                            Go to Dashboard
                        </a>
                    @else
                        <div class="space-y-4">
                            <a href="{{ route('login') }}" 
                               class="w-full block text-center px-6 py-4 bg-white text-blue-600 
                                      font-semibold rounded-xl border-2 border-blue-600 
                                      hover:bg-blue-50 transition duration-300 ease-in-out transform 
                                      hover:scale-105 hover:shadow-md focus:outline-none focus:ring-2 
                                      focus:ring-blue-500 focus:ring-offset-2">
                                Log In
                            </a>
                            
                            <a href="{{ route('register') }}" 
                               class="w-full block text-center px-6 py-4 bg-blue-600 text-white 
                                      font-semibold rounded-xl 
                                      hover:bg-blue-700 transition duration-300 ease-in-out transform 
                                      hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 
                                      focus:ring-blue-500 focus:ring-offset-2">
                                Sign Up
                            </a>
                        </div>
                    @endauth
                </div>

                <div class="mt-8 text-center text-gray-500">
                    <p class="text-sm">
                        {{ date('Y') }} RevParts. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
