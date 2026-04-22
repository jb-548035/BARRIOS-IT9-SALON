<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Appointments') }}
            </h2>
            <a href="{{ route('appointments.create') }}" class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">
                Book Appointment
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

                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 dark:bg-red-900 dark:border-red-700 dark:text-red-200 rounded flex items-center justify-between">
                            <span>{{ session('error') }}</span>
                            <button onclick="this.parentElement.style.display='none'" class="text-red-700 dark:text-red-200 font-bold text-lg">&times;</button>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700 border-b-2 border-pink-500">
                                    <th class="px-4 py-3 text-left font-semibold">Service</th>
                                    <th class="px-4 py-3 text-left font-semibold">Date</th>
                                    <th class="px-4 py-3 text-left font-semibold">Time</th>
                                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                                    <th class="px-4 py-3 text-left font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $appointment)
                                    <tr class="border-t dark:border-gray-600 hover:bg-pink-50 dark:hover:bg-gray-700 transition">
                                        <td class="px-4 py-3 font-medium">{{ $appointment->service->name }}</td>
                                        <td class="px-4 py-3">{{ $appointment->date->format('M d, Y') }}</td>
                                        <td class="px-4 py-3">{{ $appointment->time }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                                                @if($appointment->status === 'confirmed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($appointment->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($appointment->status === 'completed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @endif">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 space-x-2">
                                            <a href="{{ route('appointments.show', $appointment) }}" class="text-blue-500 hover:text-blue-700 font-semibold">View</a>
                                            <a href="{{ route('appointments.edit', $appointment) }}" class="text-yellow-500 hover:text-yellow-700 font-semibold">Edit</a>
                                            @if(!$appointment->payment && $appointment->status !== 'cancelled')
                                                <a href="{{ route('payments.create', $appointment) }}" class="text-green-500 hover:text-green-700 font-semibold">Pay</a>
                                            @endif
                                            <form method="POST" action="{{ route('appointments.destroy', $appointment) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 font-semibold" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                            No appointments found. <a href="{{ route('appointments.create') }}" class="text-pink-500 hover:text-pink-700 font-semibold">Book one now</a>
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