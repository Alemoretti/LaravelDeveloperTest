@extends('layouts.app')

@section('title', $planet->name . ' - Star Wars Planets')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('planets.index') }}" 
           class="inline-flex items-center text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Planets
        </a>
    </div>

    <!-- Planet Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 px-6 py-8">
            <h1 class="text-3xl font-bold text-white mb-2">{{ $planet->name }}</h1>
            <p class="text-purple-100">Planet ID: {{ $planet->swapi_id }} | {{ $planet->characters_count }} {{ Str::plural('Resident', $planet->characters_count) }}</p>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Physical Characteristics -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        Physical Characteristics
                    </h2>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diameter</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                @if($planet->diameter && $planet->diameter !== 'unknown')
                                    {{ number_format($planet->diameter) }} km
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">Unknown</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Rotation Period</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                @if($planet->rotation_period && $planet->rotation_period !== 'unknown')
                                    {{ $planet->rotation_period }} hours
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">Unknown</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Orbital Period</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                @if($planet->orbital_period && $planet->orbital_period !== 'unknown')
                                    {{ $planet->orbital_period }} days
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">Unknown</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gravity</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                {{ $planet->gravity !== 'unknown' ? $planet->gravity : 'Unknown' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Environmental Characteristics -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        Environment
                    </h2>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Climate</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                {{ $planet->climate !== 'unknown' ? ucfirst($planet->climate) : 'Unknown' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terrain</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                {{ $planet->terrain !== 'unknown' ? ucfirst($planet->terrain) : 'Unknown' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Surface Water</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                @if($planet->surface_water && $planet->surface_water !== 'unknown')
                                    {{ $planet->surface_water }}%
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">Unknown</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Population Information -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        Population
                    </h2>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Population</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                @if($planet->population && $planet->population > 0)
                                    {{ number_format($planet->population) }}
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">Unknown</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Known Residents</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                {{ $planet->characters_count }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Metadata -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Metadata</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Added to Database</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $planet->created_at->format('F j, Y g:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $planet->updated_at->format('F j, Y g:i A') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Residents Section -->
    @if($residents->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Known Residents</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($residents as $resident)
                    <a href="{{ route('characters.show', $resident) }}" 
                       class="bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 p-4 hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-600 transition">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-2">{{ $resident->name }}</h3>
                        <dl class="space-y-1 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Gender:</dt>
                                <dd class="text-gray-900 dark:text-gray-100 font-medium">{{ ucfirst($resident->gender) }}</dd>
                            </div>
                            @if($resident->birth_year && $resident->birth_year !== 'unknown')
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Birth Year:</dt>
                                <dd class="text-gray-900 dark:text-gray-100 font-medium">{{ $resident->birth_year }}</dd>
                            </div>
                            @endif
                        </dl>
                    </a>
                @endforeach
            </div>
            
            <!-- Residents Pagination -->
            @if($residents->hasPages())
                <div class="mt-6">
                    {{ $residents->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-8 text-center">
            <p class="text-gray-500 dark:text-gray-400">No known residents for this planet in the database.</p>
        </div>
    @endif
</div>
@endsection

