<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Star Wars Data Hub')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-gray-50 via-gray-100 to-gray-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 text-gray-900 dark:text-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Logo Section -->
            <div class="flex items-center justify-between py-4 border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('characters.index') }}" class="flex items-center space-x-3 group">
                    <span class="text-4xl group-hover:scale-110 transition-transform duration-200">⭐</span>
                    <div>
                        <div class="text-2xl font-bold bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600 dark:from-yellow-300 dark:via-yellow-400 dark:to-yellow-500 bg-clip-text text-transparent">
                            Star Wars Data Hub
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Powered by SWAPI</div>
                    </div>
                </a>
            </div>
            
            <!-- Tabs Section -->
            <div class="hidden sm:flex -mb-px space-x-1">
                <a href="{{ route('characters.index') }}" 
                   class="@if(request()->routeIs('characters.*')) border-b-4 border-indigo-600 bg-gradient-to-t from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent text-indigo-700 dark:text-indigo-300 @else border-b-4 border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600 @endif inline-flex items-center px-8 py-4 text-sm font-bold transition-all duration-200">
                    <span class="text-xl mr-2">👤</span> Characters
                </a>
                <a href="{{ route('planets.index') }}" 
                   class="@if(request()->routeIs('planets.*')) border-b-4 border-indigo-600 bg-gradient-to-t from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent text-indigo-700 dark:text-indigo-300 @else border-b-4 border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600 @endif inline-flex items-center px-8 py-4 text-sm font-bold transition-all duration-200">
                    <span class="text-xl mr-2">🌍</span> Planets
                </a>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div class="sm:hidden border-t border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm">
            <div class="flex">
                <a href="{{ route('characters.index') }}" 
                   class="@if(request()->routeIs('characters.*')) border-b-4 border-indigo-600 bg-gradient-to-t from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent text-indigo-700 dark:text-indigo-300 @else border-b-4 border-transparent text-gray-600 dark:text-gray-400 @endif flex-1 text-center px-4 py-4 text-sm font-bold transition-all">
                    <span class="block text-2xl mb-1">👤</span>
                    Characters
                </a>
                <a href="{{ route('planets.index') }}" 
                   class="@if(request()->routeIs('planets.*')) border-b-4 border-indigo-600 bg-gradient-to-t from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent text-indigo-700 dark:text-indigo-300 @else border-b-4 border-transparent text-gray-600 dark:text-gray-400 @endif flex-1 text-center px-4 py-4 text-sm font-bold transition-all">
                    <span class="block text-2xl mb-1">🌍</span>
                    Planets
                </a>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                Data from <a href="https://swapi.dev" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline">SWAPI - The Star Wars API</a>
            </p>
        </div>
    </footer>
</body>
</html>

