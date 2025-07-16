<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar'); // adjust path if needed
    }

    public function events()
    {
        $bookings = Booking::with('user')->get();

        $events = $bookings->map(function ($booking) {
            return [
                'title' => $booking->title . ' - ' . ($booking->user->name ?? 'User'),
                'start' => $booking->date . 'T' . \Carbon\Carbon::parse($booking->start_time)->format('H:i:s'),
                'end'   => $booking->date . 'T' . \Carbon\Carbon::parse($booking->end_time)->format('H:i:s'),
                'url'   => url('/my-bookings/' . $booking->id),
                'color' => '#dc3545',
            ];
        });

        return response()->json($events);
    }
}