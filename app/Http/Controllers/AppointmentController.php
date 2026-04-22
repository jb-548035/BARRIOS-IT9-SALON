<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(): View
    {
        $appointments = Appointment::with('service', 'user')->where('user_id', Auth::id())->orderBy('date', 'asc')->orderBy('time', 'asc')->get();
        return view('appointments.index', compact('appointments'));
    }

    public function create(): View
    {
        $services = Service::all();
        return view('appointments.create', compact('services'));
    }

    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        try {
            $dateTime = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time);

            if ($dateTime->isPast()) {
                return back()->with('error', 'Cannot book appointments in the past.')->withInput();
            }

            // Check for duplicate appointment
            $existing = Appointment::where('service_id', $request->service_id)
                ->where('date', $request->date)
                ->where('time', $request->time)
                ->exists();

            if ($existing) {
                return back()->with('error', 'This time slot is already booked for this service.')->withInput();
            }

            Appointment::create([
                'user_id' => Auth::id(),
                ...$request->validated(),
            ]);

            return redirect()->route('appointments.index')->with('success', 'Appointment created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create appointment. ' . $e->getMessage()]);
        }
    }

    public function show(Appointment $appointment): View
    {
        $this->authorize('view', $appointment);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment): View
    {
        $this->authorize('update', $appointment);
        $services = Service::all();
        return view('appointments.edit', compact('appointment', 'services'));
    }

    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $this->authorize('update', $appointment);

        try {
            $request->validate([
                'service_id' => 'required|exists:services,id',
                'date' => 'required|date|after_or_equal:today',
                'time' => 'required|date_format:H:i',
                'status' => 'required|in:pending,confirmed,completed,cancelled',
                'notes' => 'nullable|string|max:500',
            ]);

            $dateTime = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time);

            if ($dateTime->isPast()) {
                return back()->with('error', 'Cannot book appointments in the past.')->withInput();
            }

            // Check for duplicate excluding current
            $existing = Appointment::where('service_id', $request->service_id)
                ->where('date', $request->date)
                ->where('time', $request->time)
                ->where('id', '!=', $appointment->id)
                ->exists();

            if ($existing) {
                return back()->with('error', 'This time slot is already booked for this service.')->withInput();
            }

            $appointment->update($request->all());

            return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update appointment. ' . $e->getMessage()]);
        }
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $this->authorize('delete', $appointment);
        
        try {
            $appointment->delete();
            return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to delete appointment. ' . $e->getMessage()]);
        }
    }
}
