@extends('layouts.app')

@section('title', 'Star Wars Characters')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Star Wars Characters</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Browse and search characters from the Star Wars universe</p>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <form method="GET" action="{{ route('characters.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                    <input type="text" 
                           name="search" 
                           id="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search by name, gender, birth year..."
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <!-- Gender Filter -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gender</label>
                    <select name="gender" 
                            id="gender"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Genders</option>
                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="n/a" {{ request('gender') == 'n/a' ? 'selected' : '' }}>N/A</option>
                    </select>
                </div>

                <!-- Sort By -->
                <div>
                    <label for="sort_by" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort By</label>
                    <select name="sort_by" 
                            id="sort_by"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="height" {{ request('sort_by') == 'height' ? 'selected' : '' }}>Height</option>
                        <option value="mass" {{ request('sort_by') == 'mass' ? 'selected' : '' }}>Mass</option>
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Recently Added</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm transition">
                    Apply Filters
                </button>
                <a href="{{ route('characters.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-md shadow-sm transition">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Results Count -->
    <div class="mb-4">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Showing <span class="font-medium">{{ $characters->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $characters->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $characters->total() }}</span> characters
        </p>
    </div>

    <!-- Characters Grid -->
    @if($characters->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            @foreach($characters as $character)
                <a href="{{ route('characters.show', $character) }}" 
                   class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-600 transition">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $character->name }}</h3>
                    <dl class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Gender:</dt>
                            <dd class="text-gray-900 dark:text-gray-100 font-medium">{{ ucfirst($character->gender) }}</dd>
                        </div>
                        @if($character->birth_year && $character->birth_year !== 'unknown')
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Birth Year:</dt>
                            <dd class="text-gray-900 dark:text-gray-100 font-medium">{{ $character->birth_year }}</dd>
                        </div>
                        @endif
                        @if($character->homeworld)
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Homeworld:</dt>
                            <dd class="text-indigo-600 dark:text-indigo-400 font-medium">{{ $character->homeworld->name }}</dd>
                        </div>
                        @endif
                    </dl>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $characters->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
            <p class="text-gray-500 dark:text-gray-400">No characters found. Try adjusting your filters.</p>
        </div>
    @endif
</div>
@endsection

