<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Welcome to Barrios Salon Management</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('services.index') }}" class="block p-4 bg-pink-100 dark:bg-pink-900 rounded-lg hover:bg-pink-200 dark:hover:bg-pink-800 transition">
                            <h4 class="font-semibold text-pink-800 dark:text-pink-200">Services</h4>
                            <p class="text-sm text-pink-600 dark:text-pink-400">Manage salon services</p>
                        </a>
                        <a href="{{ route('appointments.index') }}" class="block p-4 bg-purple-100 dark:bg-purple-900 rounded-lg hover:bg-purple-200 dark:hover:bg-purple-800 transition">
                            <h4 class="font-semibold text-purple-800 dark:text-purple-200">Appointments</h4>
                            <p class="text-sm text-purple-600 dark:text-purple-400">Book and manage appointments</p>
                        </a>
                        <a href="{{ route('payments.index') }}" class="block p-4 bg-blue-100 dark:bg-blue-900 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800 transition">
                            <h4 class="font-semibold text-blue-800 dark:text-blue-200">Payments</h4>
                            <p class="text-sm text-blue-600 dark:text-blue-400">View payment history</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
