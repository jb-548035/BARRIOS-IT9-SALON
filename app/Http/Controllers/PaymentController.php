<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(): View
    {
        $payments = Payment::with('appointment.service', 'appointment.user')
            ->whereHas('appointment', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return view('payments.index', compact('payments'));
    }

    public function create(Appointment $appointment): View
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Check if appointment already has a payment
        if ($appointment->payment) {
            return redirect()->route('appointments.show', $appointment)->with('error', 'This appointment already has a payment recorded.');
        }

        return view('payments.create', compact('appointment'));
    }

    public function store(Request $request, Appointment $appointment): RedirectResponse
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        try {
            $request->validate([
                'payment_method' => 'nullable|string|max:50',
            ]);

            // Check if payment already exists
            if ($appointment->payment) {
                return redirect()->route('appointments.show', $appointment)->with('error', 'This appointment already has a payment.');
            }

            Payment::create([
                'appointment_id' => $appointment->id,
                'amount' => $appointment->service->price,
                'payment_method' => $request->payment_method,
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            return redirect()->route('payments.index')->with('success', 'Payment processed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to process payment. ' . $e->getMessage()]);
        }
    }

    public function show(Payment $payment): View
    {
        if ($payment->appointment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        return view('payments.show', compact('payment'));
    }
}
