@extends('layouts.app')

@section('title', $character->name . ' - Star Wars Characters')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('characters.index') }}" 
           class="inline-flex items-center text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Characters
        </a>
    </div>

    <!-- Character Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-8">
            <h1 class="text-3xl font-bold text-white mb-2">{{ $character->name }}</h1>
            <p class="text-indigo-100">Character ID: {{ $character->swapi_id }}</p>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Physical Characteristics -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        Physical Characteristics
                    </h2>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Height</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                @if($character->height && $character->height !== 'unknown')
                                    {{ $character->height }} cm
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">Unknown</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Mass</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                @if($character->mass && $character->mass !== 'unknown')
                                    {{ $character->mass }} kg
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">Unknown</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Hair Color</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                {{ $character->hair_color !== 'unknown' ? ucfirst($character->hair_color) : 'Unknown' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Skin Color</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                {{ $character->skin_color !== 'unknown' ? ucfirst($character->skin_color) : 'Unknown' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Eye Color</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                {{ $character->eye_color !== 'unknown' ? ucfirst($character->eye_color) : 'Unknown' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Personal Information -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        Personal Information
                    </h2>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Birth Year</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                {{ $character->birth_year !== 'unknown' ? $character->birth_year : 'Unknown' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gender</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                {{ ucfirst($character->gender) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Homeworld</dt>
                            <dd class="mt-1">
                                @if($character->homeworld)
                                    <a href="{{ route('planets.show', $character->homeworld) }}" 
                                       class="inline-flex items-center text-base text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                                        {{ $character->homeworld->name }}
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">Unknown</span>
                                @endif
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
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $character->created_at->format('F j, Y g:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $character->updated_at->format('F j, Y g:i A') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection

