<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Appointment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('appointments.update', $appointment) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-input-label for="service_id" :value="__('Service')" />
                            <select id="service_id" name="service_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id', $appointment->service_id) == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} - ${{ number_format($service->price, 2) }} ({{ $service->duration }} mins)
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('service_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="date" :value="__('Date')" />
                            <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date', $appointment->date->format('Y-m-d'))" required min="{{ date('Y-m-d') }}" />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="time" :value="__('Time')" />
                            <select id="time" name="time" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
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
                                    // Format existing time to H:i (e.g., 09:00:00 to 09:00) for comparison
                                    $currentTime = \Carbon\Carbon::parse($appointment->time)->format('H:i');
                                @endphp

                                @foreach($slots as $value => $label)
                                    <option value="{{ $value }}" {{ old('time', $currentTime) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('time')" class="mt-2" />
                        </div>


                        <div class="mb-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="pending" {{ old('status', $appointment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="notes" :value="__('Notes (Optional)')" />
                            <textarea id="notes" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="notes">{{ old('notes', $appointment->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Update Appointment') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dateInput = document.getElementById('date');
            const timeSelect = document.getElementById('time');

            function updateTimeSlots() {
                const selectedDate = dateInput.value;
                const now = new Date();
                const todayStr = now.toISOString().split('T')[0];
                
                Array.from(timeSelect.options).forEach(option => {
                    if (!option.value) return;

                    if (selectedDate === todayStr) {
                        const [hours, minutes] = option.value.split(':');
                        const slotTime = new Date();
                        slotTime.setHours(hours, minutes, 0, 0);
                        
                        // Disable if the time has already passed today
                        option.disabled = slotTime <= now;
                    } else {
                        option.disabled = false;
                    }
                });
            }

            dateInput.addEventListener('change', updateTimeSlots);
            updateTimeSlots(); // Run on page load
        });
    </script>

</x-app-layout>