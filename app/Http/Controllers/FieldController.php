<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FieldController extends Controller
{
    public function index(Request $request)
    {
        $sport = $request->input('sport');
        $query = $request->input('q');

        // Allowable sports as per feedback (only padel, tennis, badminton)
        $allowedSports = ['padel', 'tennis', 'badminton'];

        $fieldsQuery = Field::active();

        if ($sport && in_array($sport, $allowedSports)) {
            $fieldsQuery->bySport($sport);
        }

        if ($query) {
            $fieldsQuery->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('location', 'like', "%{$query}%");
            });
        }

        $fields = $fieldsQuery->get();

        return view('fields.index', compact('fields', 'sport', 'query'));
    }

    public function show(Field $field, Request $request)
    {
        // Default to today's date if no date is specified
        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));
        
        // Ensure the date is valid and format it
        try {
            $dateObj = Carbon::parse($selectedDate);
            // Don't let users book in the past
            if ($dateObj->isBefore(Carbon::today())) {
                $selectedDate = Carbon::today()->format('Y-m-d');
                $dateObj = Carbon::today();
            }
        } catch (\Exception $e) {
            $selectedDate = Carbon::today()->format('Y-m-d');
            $dateObj = Carbon::today();
        }

        // Define our fixed time slots (matching the Stitch detail mock)
        $slots = [
            ['start' => '09:00:00', 'end' => '10:30:00', 'label' => '09:00 AM'],
            ['start' => '10:30:00', 'end' => '12:00:00', 'label' => '10:30 AM'],
            ['start' => '13:00:00', 'end' => '14:30:00', 'label' => '01:00 PM'],
            ['start' => '14:30:00', 'end' => '16:00:00', 'label' => '02:30 PM'],
            ['start' => '16:00:00', 'end' => '17:30:00', 'label' => '04:00 PM'],
            ['start' => '17:30:00', 'end' => '19:00:00', 'label' => '05:30 PM'],
        ];

        // Fetch bookings for this field on the selected date to mark slots as booked
        $bookings = Booking::where('field_id', $field->id)
            ->whereDate('booking_date', $selectedDate)
            ->where('status', '!=', 'cancelled')
            ->get();

        $slotsWithAvailability = [];
        foreach ($slots as $slot) {
            $isBooked = false;
            foreach ($bookings as $booking) {
                // Check for time overlap
                $bStart = $booking->start_time;
                $bEnd = $booking->end_time;
                
                // standard overlap condition: (StartA < EndB) and (EndA > StartB)
                if ($slot['start'] < $bEnd && $slot['end'] > $bStart) {
                    $isBooked = true;
                    break;
                }
            }
            
            $slotsWithAvailability[] = array_merge($slot, ['is_booked' => $isBooked]);
        }

        $reviews = $field->reviews()->with('user')->latest()->get();

        return view('fields.show', compact('field', 'selectedDate', 'dateObj', 'slotsWithAvailability', 'reviews'));
    }
}
