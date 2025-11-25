@extends('layouts.app')

@section('title', 'Star Wars Characters')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header with Gradient -->
    <div class="mb-10 text-center">
        <h1 class="text-5xl font-extrabold mb-4">
            <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 dark:from-blue-400 dark:via-purple-400 dark:to-pink-400 bg-clip-text text-transparent">
                Star Wars Characters
            </span>
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
            Explore the heroes, villains, and legends from a galaxy far, far away
        </p>
    </div>

    <!-- Search and Filters with Better Design -->
    <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-lg rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-8 mb-8">
        <form method="GET" action="{{ route('characters.index') }}" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2 flex items-center">
                        <span class="mr-2">🔍</span> Search
                    </label>
                    <input type="text" 
                           name="search" 
                           id="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search by name, gender, birth year..."
                           class="w-full rounded-xl border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 px-4 py-3 transition-all">
                </div>

                <!-- Gender Filter -->
                <div>
                    <label for="gender" class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2 flex items-center">
                        <span class="mr-2">⚧</span> Gender
                    </label>
                    <select name="gender" 
                            id="gender"
                            class="w-full rounded-xl border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 px-4 py-3 transition-all">
                        <option value="">All Genders</option>
                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="n/a" {{ request('gender') == 'n/a' ? 'selected' : '' }}>N/A</option>
                    </select>
                </div>

                <!-- Sort By -->
                <div>
                    <label for="sort_by" class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2 flex items-center">
                        <span class="mr-2">⬍⬍</span> Sort By
                    </label>
                    <select name="sort_by" 
                            id="sort_by"
                            class="w-full rounded-xl border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 px-4 py-3 transition-all">
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="height" {{ request('sort_by') == 'height' ? 'selected' : '' }}>Height</option>
                        <option value="mass" {{ request('sort_by') == 'mass' ? 'selected' : '' }}>Mass</option>
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Recently Added</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3 justify-center">
                <button type="submit" 
                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-105">
                    ✨ Apply Filters
                </button>
                <a href="{{ route('characters.index') }}" 
                   class="inline-flex items-center px-8 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-105">
                    🔄 Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Results Count -->
    <div class="mb-6 flex items-center justify-between">
        <div class="inline-flex items-center bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-full px-6 py-3 shadow-lg">
            <span class="text-sm text-gray-600 dark:text-gray-300">
                Showing <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $characters->firstItem() ?? 0 }}</span> to 
                <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $characters->lastItem() ?? 0 }}</span> of 
                <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $characters->total() }}</span> characters
            </span>
        </div>
    </div>

    <!-- Characters Grid with Enhanced Cards -->
    @if($characters->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($characters as $character)
                <a href="{{ route('characters.show', $character) }}" 
                   class="group bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-2xl shadow-xl border-2 border-gray-200 dark:border-gray-700 p-6 hover:shadow-2xl hover:border-indigo-400 dark:hover:border-indigo-500 transition-all duration-300 hover:scale-105 hover:-translate-y-1">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                        {{ $character->name }}
                    </h3>
                    
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700/50 rounded-lg px-3 py-2">
                            <dt class="text-gray-600 dark:text-gray-400 font-medium flex items-center">
                                <span class="mr-2">⚧</span> Gender
                            </dt>
                            <dd class="text-gray-900 dark:text-gray-100 font-bold">{{ ucfirst($character->gender) }}</dd>
                        </div>
                        @if($character->birth_year && $character->birth_year !== 'unknown')
                        <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700/50 rounded-lg px-3 py-2">
                            <dt class="text-gray-600 dark:text-gray-400 font-medium flex items-center">
                                <span class="mr-2">📅</span> Birth Year
                            </dt>
                            <dd class="text-gray-900 dark:text-gray-100 font-bold">{{ $character->birth_year }}</dd>
                        </div>
                        @endif
                        @if($character->homeworld)
                        <div class="flex justify-between items-center bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-lg px-3 py-2">
                            <dt class="text-gray-600 dark:text-gray-400 font-medium flex items-center">
                                <span class="mr-2">🌍</span> Homeworld
                            </dt>
                            <dd class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $character->homeworld->name }}</dd>
                        </div>
                        @endif
                    </dl>
                    
                    <!-- View Details Arrow -->
                    <div class="mt-4 flex items-center text-indigo-600 dark:text-indigo-400 font-semibold text-sm group-hover:translate-x-2 transition-transform">
                        View Details →
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $characters->links() }}
        </div>
    @else
        <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-2xl shadow-xl border-2 border-gray-200 dark:border-gray-700 p-12 text-center">
            <div class="text-6xl mb-4">🔍</div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No Characters Found</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Try adjusting your search filters or clear them to see all characters.</p>
            <a href="{{ route('characters.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-105">
                🔄 Clear Filters
            </a>
        </div>
    @endif
</div>
@endsection

