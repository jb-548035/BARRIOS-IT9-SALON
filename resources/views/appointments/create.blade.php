<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Book Appointment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 dark:bg-red-900 dark:border-red-700 dark:text-red-200 rounded">
                            <strong>Validation Errors:</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 dark:bg-red-900 dark:border-red-700 dark:text-red-200 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('appointments.store') }}" class="space-y-4">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="service_id" :value="__('Select Service')" />
                            <select id="service_id" name="service_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Choose a service...</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} - ${{ number_format($service->price, 2) }} ({{ $service->duration }} mins)
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('service_id')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <x-input-label for="date" :value="__('Appointment Date')" />
                                <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date')" required min="{{ date('Y-m-d') }}" />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>

                        <div class="mb-4">
                            <x-input-label for="time" :value="__('Appointment Time')" />
                            <select id="time" name="time" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Select a time slot</option>
                                @php
                                    $slots = [
                                        '09:00' => '9:00 AM',
                                        '10:00' => '10:00 AM',
                                        '11:00' => '11:00 AM',
                                        '13:00' => '1:00 PM',
                                        '14:00' => '2:00 PM',
                                        '15:00' => '3:00 PM',
                                        '16:00' => '4:00 PM',
                                        '17:00' => '5:00 PM',
                                    ];
                                @endphp

                                @foreach($slots as $value => $label)
                                    <option value="{{ $value }}" {{ old('time') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('time')" class="mt-2" />
                            <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">Operating Hours: 9AM - 6PM (Closed 12PM-1PM)</p>
                        </div>

                        </div>

                        <div class="mb-4">
                            <x-input-label for="notes" :value="__('Additional Notes (Optional)')" />
                            <textarea id="notes" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="notes" placeholder="Any special requests...">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('appointments.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                            <x-primary-button>
                                {{ __('Book Appointment') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const dateInput = document.getElementById('date');
        const timeSelect = document.getElementById('time');

        dateInput.addEventListener('change', function() {
            const selectedDate = this.value;
            const now = new Date();
            const todayStr = now.toISOString().split('T')[0];
            
            // Loop through all options in the select
            Array.from(timeSelect.options).forEach(option => {
                if (!option.value) return; // Skip the placeholder

                if (selectedDate === todayStr) {
                    const [hours, minutes] = option.value.split(':');
                    const slotTime = new Date();
                    slotTime.setHours(hours, minutes, 0, 0);

                    // Disable the option if it has already passed today
                    option.disabled = slotTime <= now;
                } else {
                    // Re-enable all options for future dates
                    option.disabled = false;
                }
            });
            
            // Reset selection if the currently selected one is now disabled
            if (timeSelect.selectedOptions[0]?.disabled) {
                timeSelect.value = "";
            }
        });
    </script>

</x-app-layout>