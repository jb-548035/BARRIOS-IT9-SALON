<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Services') }}
            </h2>
            <a href="{{ route('services.create') }}" class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">
                Add Service
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 dark:bg-green-900 dark:border-green-700 dark:text-green-200 rounded flex items-center justify-between">
                            <span>{{ session('success') }}</span>
                            <button onclick="this.parentElement.style.display='none'" class="text-green-700 dark:text-green-200 font-bold text-lg">&times;</button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 dark:bg-red-900 dark:border-red-700 dark:text-red-200 rounded">
                            <strong>Error:</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700 border-b-2 border-pink-500">
                                    <th class="px-4 py-3 text-left font-semibold">Name</th>
                                    <th class="px-4 py-3 text-left font-semibold">Price</th>
                                    <th class="px-4 py-3 text-left font-semibold">Duration</th>
                                    <th class="px-4 py-3 text-left font-semibold">Description</th>
                                    <th class="px-4 py-3 text-left font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($services as $service)
                                    <tr class="border-t dark:border-gray-600 hover:bg-pink-50 dark:hover:bg-gray-700 transition">
                                        <td class="px-4 py-3 font-medium">{{ $service->name }}</td>
                                        <td class="px-4 py-3 text-green-600 dark:text-green-400 font-bold">₱{{ number_format($service->price, 2) }}</td>
                                        <td class="px-4 py-3">{{ $service->duration }} mins</td>
                                        <td class="px-4 py-3" title="{{ $service->description }}">{{ Str::limit($service->description ?? 'N/A', 50, '...') }}</td>
                                        <td class="px-4 py-3 space-x-2">
                                            <a href="{{ route('services.show', $service) }}" class="text-blue-500 hover:text-blue-700 font-semibold">View</a>
                                            <a href="{{ route('services.edit', $service) }}" class="text-yellow-500 hover:text-yellow-700 font-semibold">Edit</a>
                                            <form method="POST" action="{{ route('services.destroy', $service) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 font-semibold" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                            No services found. <a href="{{ route('services.create') }}" class="text-pink-500 hover:text-pink-700 font-semibold">Create one now</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>