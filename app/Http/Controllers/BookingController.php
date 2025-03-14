<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function createPublicBooking()
    {
        return view('public.booking-form');
    }
 
    public function storePublicBooking(Request $request)
    {
        $data = $request->validate([
            'artist_id'   => 'required|exists:artists,id',
            'event_type'  => 'required|string|max:255',
            'event_date'  => 'required|date',
            'venue'       => 'required|string|max:255',
            'budget'      => 'required|string|max:255',
            'full_name'   => 'required|string|max:255',
            'email'       => 'required|email',
            'phone'       => 'required|string|max:20',
            'details'     => 'nullable|string',
        ]);

        $data['user_id'] = Auth::check() ? Auth::id() : null;

        $booking = Booking::create($data);

        $otp = rand(1000, 9999);
        session(['booking_otp' => $otp, 'current_booking_id' => $booking->id]);

        session()->flash('showOtpModal', true);

        return redirect()->route('bookings.createPublicBooking')
                         ->with('success', 'Booking created! Please verify OTP.');
    }

 
    public function verifyOtp(Request $request, Booking $booking)
    {
        $request->validate([
            'otp' => 'required|digits:4'
        ]);

        $savedOtp = session('booking_otp') ?? 1234;

        if ($request->otp == $savedOtp) {
            $booking->update(['booking_status' => 'confirmed']);
            session()->forget('booking_otp');
            session()->forget('current_booking_id');
            session()->flash('bookingSuccess', true);
            return redirect()->route('bookings.createPublicBooking')
                             ->with('success', 'Booking confirmed!');
        } else {
            return redirect()->route('bookings.createPublicBooking')
                             ->withErrors(['otp' => 'Invalid OTP, please try again.'])
                             ->with('showOtpModal', true);
        }
    }
 
    public function adminIndex()
    {
        $bookings = Booking::with(['user', 'artist'])->orderBy('created_at', 'desc')->get();
        return view('AdminPanel.bookings.index', compact('bookings'));
    }

 
    public function artistIndex()
    {
        $bookings = Booking::with('user')
            ->where('artist_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('ArtistPanel.bookings.index', compact('bookings'));
    }

    public function successPage()
    {
        return view('public.booking-success');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'booking_status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $booking->update($data);
        return redirect()->back()->with('success', 'Booking status updated successfully.');
    }
}
