<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Artist;
use App\Models\Booking;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        return view('AdminPanel.dashboard.dashboard');
    }

    public function overview()
    {
        $totalUsers    = User::count();
        $totalArtists  = Artist::count();
        $totalBookings = Booking::count();
        $totalPayments = Payment::count();

        return view('AdminPanel.dashboard.overview', compact('totalUsers', 'totalArtists', 'totalBookings', 'totalPayments'));
    }

    public function statistics()
    {
        $stats = [
            'users_per_day'    => User::selectRaw('DATE(created_at) as date, COUNT(*) as count')->groupBy('date')->get(),
            'bookings_per_day' => Booking::selectRaw('DATE(created_at) as date, COUNT(*) as count')->groupBy('date')->get(),
        ];

        return view('AdminPanel.dashboard.statistics', compact('stats'));
    }
}
