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
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('characters.index') }}" class="text-xl font-bold text-gray-900 dark:text-white">
                            ⭐ Star Wars Data Hub
                        </a>
                    </div>
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-8">
                        <a href="{{ route('characters.index') }}" 
                           class="@if(request()->routeIs('characters.*')) border-indigo-500 text-gray-900 dark:text-white @else border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition">
                            Characters
                        </a>
                        <a href="{{ route('planets.index') }}" 
                           class="@if(request()->routeIs('planets.*')) border-indigo-500 text-gray-900 dark:text-white @else border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition">
                            Planets
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div class="sm:hidden border-t border-gray-200 dark:border-gray-700">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('characters.index') }}" 
                   class="@if(request()->routeIs('characters.*')) bg-indigo-50 dark:bg-indigo-900/20 border-indigo-500 text-indigo-700 dark:text-indigo-300 @else border-transparent text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-800 dark:hover:text-gray-200 @endif block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition">
                    Characters
                </a>
                <a href="{{ route('planets.index') }}" 
                   class="@if(request()->routeIs('planets.*')) bg-indigo-50 dark:bg-indigo-900/20 border-indigo-500 text-indigo-700 dark:text-indigo-300 @else border-transparent text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-800 dark:hover:text-gray-200 @endif block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition">
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

