<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Payment History') }}
        </h2>
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
                                    <th class="px-4 py-3 text-left font-semibold">Appointment Date</th>
                                    <th class="px-4 py-3 text-left font-semibold">Amount</th>
                                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                                    <th class="px-4 py-3 text-left font-semibold">Paid Date</th>
                                    <th class="px-4 py-3 text-left font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr class="border-t dark:border-gray-600 hover:bg-pink-50 dark:hover:bg-gray-700 transition">
                                        <td class="px-4 py-3 font-medium">{{ $payment->appointment->service->name }}</td>
                                        <td class="px-4 py-3">{{ $payment->appointment->date->format('M d, Y') }} at {{ $payment->appointment->time }}</td>
                                        <td class="px-4 py-3 font-bold text-green-600 dark:text-green-400">₱{{ number_format($payment->amount, 2) }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                                                @if($payment->status === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($payment->status === 'unpaid') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($payment->status === 'refunded') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @endif">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">{{ $payment->paid_at ? $payment->paid_at->format('M d, Y') : 'N/A' }}</td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('payments.show', $payment) }}" class="text-blue-500 hover:text-blue-700 font-semibold">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                            No payments found. <a href="{{ route('appointments.index') }}" class="text-pink-500 hover:text-pink-700 font-semibold">Book an appointment</a>
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