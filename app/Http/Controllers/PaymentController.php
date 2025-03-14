<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        return view('AdminPanel.payments.index', compact('payments'));
    }

    public function create()
    {
        return view('AdminPanel.payments.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'booking_id'      => 'required|exists:bookings,id',
            'payment_method'  => 'required|string|max:255',
            'amount'          => 'required|numeric',
            'transaction_id'  => 'required|string|unique:payments,transaction_id',
            'payment_status'  => 'required|in:pending,success,failed',
        ]);

        Payment::create($data);
        return redirect()->route('payments.index')->with('success', 'Payment created successfully.');
    }

    public function show(Payment $payment)
    {
        return view('AdminPanel.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        return view('AdminPanel.payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'booking_id'      => 'required|exists:bookings,id',
            'payment_method'  => 'required|string|max:255',
            'amount'          => 'required|numeric',
            'transaction_id'  => 'required|string|unique:payments,transaction_id,'.$payment->id,
            'payment_status'  => 'required|in:pending,success,failed',
        ]);

        $payment->update($data);
        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }

    public function refunds()
    {
        $refunds = Payment::where('payment_status', 'failed')->get();
        return view('AdminPanel.payments.refunds', compact('refunds'));
    }

    public function disputes()
    {
        $disputes = Payment::where('payment_status', 'pending')->get();
        return view('AdminPanel.payments.disputes', compact('disputes'));
    }
}
