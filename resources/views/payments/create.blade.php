<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Process Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded">
                        <h3 class="font-bold mb-3">Appointment Summary</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Service</p>
                                <p class="font-semibold">{{ $appointment->service->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Date & Time</p>
                                <p class="font-semibold">{{ $appointment->date->format('M d, Y') }} at {{ $appointment->time }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Duration</p>
                                <p class="font-semibold">{{ $appointment->service->duration }} minutes</p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Amount Due</p>
                                <p class="font-bold text-green-600 text-lg">${{ number_format($appointment->service->price, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('payments.store', $appointment) }}">
                        @csrf

                        <input type="hidden" name="amount" value="{{ $appointment->service->price }}">

                        <div class="mb-4">
                            <x-input-label for="payment_method" :value="__('Payment Method')" />
                            <select id="payment_method" name="payment_method" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Select payment method</option>
                                <option value="cash">Cash</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="debit_card">Debit Card</option>
                                <option value="check">Check</option>
                            </select>
                            <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                By proceeding, you confirm that you will pay ${{ number_format($appointment->service->price, 2) }} for this appointment.
                            </p>
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <a href="{{ route('appointments.show', $appointment) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Process Payment') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>