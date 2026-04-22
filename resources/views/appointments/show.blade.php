<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Appointment Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded">
                            <p class="text-gray-600 dark:text-gray-300 text-sm">Service</p>
                            <p class="text-xl font-bold text-pink-500">{{ $appointment->service->name }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded">
                            <p class="text-gray-600 dark:text-gray-300 text-sm">Customer</p>
                            <p class="text-xl font-bold">{{ $appointment->user->name }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded">
                            <p class="text-gray-600 dark:text-gray-300 text-sm">Date</p>
                            <p class="text-xl font-bold">{{ $appointment->date->format('M d, Y') }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded">
                            <p class="text-gray-600 dark:text-gray-300 text-sm">Time</p>
                            <p class="text-xl font-bold">{{ $appointment->time }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded">
                            <p class="text-gray-600 dark:text-gray-300 text-sm">Price</p>
                            <p class="text-xl font-bold text-green-500">₱{{ number_format($appointment->service->price, 2) }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded">
                            <p class="text-gray-600 dark:text-gray-300 text-sm">Duration</p>
                            <p class="text-xl font-bold">{{ $appointment->service->duration }} mins</p>
                        </div>
                    </div>

                    @if($appointment->notes)
                        <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded">
                            <p class="text-gray-600 dark:text-gray-300 text-sm">Notes</p>
                            <p>{{ $appointment->notes }}</p>
                        </div>
                    @endif

                    <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded">
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-2">Status</p>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                            @if($appointment->status === 'confirmed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($appointment->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($appointment->status === 'completed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @endif">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('appointments.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back
                        </a>
                        <div>
                            <a href="{{ route('appointments.edit', $appointment) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Edit
                            </a>
                            @if(!$appointment->payment)
                                <a href="{{ route('payments.create', $appointment) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2">
                                    Process Payment
                                </a>
                            @endif
                            <form method="POST" action="{{ route('appointments.destroy', $appointment) }}" class="inline">
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