<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Payment Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded">
                            <p class="text-gray-600 dark:text-gray-300 text-sm">Payment ID</p>
                            <p class="text-lg font-bold">#{{ $payment->id }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded">
                            <p class="text-gray-600 dark:text-gray-300 text-sm">Amount</p>
                            <p class="text-lg font-bold text-green-600">₱{{ number_format($payment->amount, 2) }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded">
                            <p class="text-gray-600 dark:text-gray-300 text-sm">Status</p>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                                @if($payment->status === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($payment->status === 'unpaid') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @elseif($payment->status === 'refunded') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @endif">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded">
                            <p class="text-gray-600 dark:text-gray-300 text-sm">Payment Method</p>
                            <p class="text-lg font-bold">{{ $payment->payment_method ? ucfirst(str_replace('_', ' ', $payment->payment_method)) : 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded mb-6">
                        <h3 class="font-bold mb-3">Appointment Details</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Service</p>
                                <p class="font-semibold">{{ $payment->appointment->service->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Customer</p>
                                <p class="font-semibold">{{ $payment->appointment->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Date & Time</p>
                                <p class="font-semibold">{{ $payment->appointment->date->format('M d, Y') }} at {{ $payment->appointment->time }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Appointment Status</p>
                                <p class="font-semibold">{{ ucfirst($payment->appointment->status) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded mb-6">
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Paid Date</p>
                        <p class="font-semibold">{{ $payment->paid_at ? $payment->paid_at->format('F d, Y \a\t h:i A') : 'Not paid' }}</p>
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('payments.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Payments
                        </a>
                        <a href="{{ route('appointments.show', $payment->appointment) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            View Appointment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>