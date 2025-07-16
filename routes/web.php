<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingCreated;
use Carbon\Carbon;
use App\Http\Controllers\CalendarController;

Route::get('/', fn () => view('auth.login'))->middleware('guest');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
    $bookings = \App\Models\Booking::with('user')->latest()->get();
    return view('bookings.index', compact('bookings'));
    })->middleware('auth')->name('dashboard');

    Route::get('/profile', function () {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    })->name('profile');

    Route::get('/my-bookings', function () {
        $bookings = Booking::with('user')->latest()->get();
        return view('bookings.index', compact('bookings'));
    });

    Route::get('/my-bookings/create', function () {
        $bookedDates = Booking::pluck('date')->toArray();
        return view('bookings.create', compact('bookedDates'));
    });

    Route::post('/my-bookings/create', function (Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'people' => 'required|integer|min:1|max:100',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $start = Carbon::parse($validated['start_time']);
        $end = Carbon::parse($validated['end_time']);

        if ($end->lte($start)) {
            return back()->withInput()->withErrors(['end_time' => 'End time must be after start time.']);
        }

        $booking = Booking::create([
            'title' => $validated['title'],
            'date' => $validated['date'],
            'people' => $validated['people'],
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'user_id' => $request->user()->id,
        ]);

        foreach (User::all() as $user) {
            $user->notify(new BookingCreated($booking));
        }

        return redirect('/my-bookings')->with('success', 'Booking created successfully!');
    });

    Route::get('/my-bookings/{id}/edit', function ($id) {
        $booking = Booking::findOrFail($id);
        abort_if($booking->user_id !== auth()->id(), 403);
        return view('bookings.edit', compact('booking'));
    });

    Route::put('/my-bookings/{id}/edit', function (Request $request, $id) {
        $booking = Booking::findOrFail($id);
        abort_if($booking->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'people' => 'required|integer|min:1|max:100',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $start = Carbon::parse($validated['start_time']);
        $end = Carbon::parse($validated['end_time']);

        if ($end->lte($start)) {
            return back()->withInput()->withErrors(['end_time' => 'End time must be after start time.']);
        }

        $booking->update([
            'title' => $validated['title'],
            'date' => $validated['date'],
            'people' => $validated['people'],
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
        ]);

        return redirect('/my-bookings')->with('success', 'Booking updated successfully!');
    });

    Route::get('/my-bookings/{id}', function ($id) {
        $booking = Booking::with('user')->findOrFail($id);
        return view('bookings.show', compact('booking'));
    });

    Route::delete('/my-bookings/{id}/delete', function ($id) {
        $booking = Booking::findOrFail($id);
        abort_if($booking->user_id !== auth()->id(), 403);
        $booking->delete();

        return redirect('/my-bookings')->with('success', 'Booking deleted successfully!');
    });

    // ✅ Notifications
    Route::post('/notifications/{id}/read', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return back();
    })->name('notifications.read');

    Route::post('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.readAll');

    Route::post('/notifications/clear-read', function () {
        auth()->user()->readNotifications()->delete();
        return back();
    })->name('notifications.clearRead');

    Route::get('/notifications', function () {
        $notifications = auth()->user()->notifications()->latest()->paginate(15);
        return view('notifications.index', compact('notifications'));
    });

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::get('/calendar/events', [CalendarController::class, 'events']);
});

require __DIR__.'/auth.php';