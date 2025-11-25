@extends('layouts.app')

@section('title', 'Star Wars Planets')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Star Wars Planets</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Explore planets from across the Star Wars galaxy</p>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <form method="GET" action="{{ route('planets.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                    <input type="text" 
                           name="search" 
                           id="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search by name, climate, terrain..."
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <!-- Climate Filter -->
                <div>
                    <label for="climate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Climate</label>
                    <input type="text" 
                           name="climate" 
                           id="climate" 
                           value="{{ request('climate') }}" 
                           placeholder="e.g., arid, temperate..."
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <!-- Sort By -->
                <div>
                    <label for="sort_by" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort By</label>
                    <select name="sort_by" 
                            id="sort_by"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="diameter" {{ request('sort_by') == 'diameter' ? 'selected' : '' }}>Diameter</option>
                        <option value="population" {{ request('sort_by') == 'population' ? 'selected' : '' }}>Population</option>
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Recently Added</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm transition">
                    Apply Filters
                </button>
                <a href="{{ route('planets.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-md shadow-sm transition">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Results Count -->
    <div class="mb-4">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Showing <span class="font-medium">{{ $planets->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $planets->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $planets->total() }}</span> planets
        </p>
    </div>

    <!-- Planets Grid -->
    @if($planets->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            @foreach($planets as $planet)
                <a href="{{ route('planets.show', $planet) }}" 
                   class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-600 transition">
                    <div class="flex items-start justify-between mb-3">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $planet->name }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                            {{ $planet->characters_count }} {{ Str::plural('resident', $planet->characters_count) }}
                        </span>
                    </div>
                    <dl class="space-y-1 text-sm">
                        @if($planet->climate && $planet->climate !== 'unknown')
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Climate:</dt>
                            <dd class="text-gray-900 dark:text-gray-100 font-medium">{{ ucfirst($planet->climate) }}</dd>
                        </div>
                        @endif
                        @if($planet->terrain && $planet->terrain !== 'unknown')
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Terrain:</dt>
                            <dd class="text-gray-900 dark:text-gray-100 font-medium">{{ ucfirst(Str::limit($planet->terrain, 20)) }}</dd>
                        </div>
                        @endif
                        @if($planet->population && $planet->population !== 'unknown' && is_numeric($planet->population))
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Population:</dt>
                            <dd class="text-gray-900 dark:text-gray-100 font-medium">{{ number_format((int)$planet->population) }}</dd>
                        </div>
                        @endif
                    </dl>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $planets->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
            <p class="text-gray-500 dark:text-gray-400">No planets found. Try adjusting your filters.</p>
        </div>
    @endif
</div>
@endsection

