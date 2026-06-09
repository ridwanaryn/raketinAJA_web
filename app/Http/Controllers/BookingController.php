<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function confirm(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $fieldId = $request->input('field_id');
        $date = $request->input('date');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');

        $field = Field::findOrFail($fieldId);

        // Calculate hours duration
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        $durationHours = $start->diffInMinutes($end) / 60;
        if ($durationHours <= 0) {
            $durationHours = 1.5; // fallback
        }

        // Pricing logic matching Stitch confirm mock
        $courtRental = $field->price_per_hour * $durationHours;
        $serviceFee = 5.50;
        $discount = round($courtRental * 0.10, 2); // 10% flash discount
        $totalDue = $courtRental + $serviceFee - $discount;

        return view('bookings.confirm', compact(
            'field', 'date', 'startTime', 'endTime', 
            'courtRental', 'serviceFee', 'discount', 'totalDue'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required',
            'total_price' => 'required|numeric',
        ]);

        $fieldId = $request->input('field_id');
        $date = $request->input('date');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');
        $totalPrice = $request->input('total_price');

        // DOUBLE BOOKING / OVERLAP PROTECTION
        // Query to check if there is an active booking on the same field, date, and overlapping times.
        // Condition: (StartA < EndB) and (EndA > StartB)
        $overlap = Booking::where('field_id', $fieldId)
            ->whereDate('booking_date', $date)
            ->where('status', '!=', 'cancelled')
            ->where(function($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            })
            ->exists();

        if ($overlap) {
            return redirect()->route('fields.show', $fieldId)
                ->with('error', 'Conflict Detected! This time slot has already been reserved by another athlete.');
        }

        // Save Booking
        Booking::create([
            'user_id' => Auth::id(),
            'field_id' => $fieldId,
            'booking_date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'total_price' => $totalPrice,
            'status' => 'confirmed',
        ]);

        return redirect()->route('bookings.index')
            ->with('success', 'Match Day Locked In! Your booking is secured.');
    }

    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['field', 'review'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('bookings.index', compact('bookings'));
    }
}
