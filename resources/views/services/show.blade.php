<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Service Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <h3 class="text-2xl font-bold mb-2">{{ $service->name }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $service->description }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded">
                            <p class="text-gray-600 dark:text-gray-300 text-sm">Price</p>
                            <p class="text-2xl font-bold text-pink-500">₱{{ number_format($service->price, 2) }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded">
                            <p class="text-gray-600 dark:text-gray-300 text-sm">Duration</p>
                            <p class="text-2xl font-bold text-purple-500">{{ $service->duration }} mins</p>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('services.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back
                        </a>
                        <div>
                            <a href="{{ route('services.edit', $service) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('services.destroy', $service) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>