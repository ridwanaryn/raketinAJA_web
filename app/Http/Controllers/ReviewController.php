<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ]);

        $bookingId = $request->input('booking_id');
        $rating = $request->input('rating');
        $reviewText = $request->input('review');

        $booking = Booking::findOrFail($bookingId);

        // Security check
        if ($booking->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized operation. You cannot review this booking.');
        }

        // Check if already reviewed
        if ($booking->review()->exists()) {
            return back()->with('error', 'Feedback has already been registered for this session.');
        }

        // Create review
        Review::create([
            'booking_id' => $bookingId,
            'user_id' => Auth::id(),
            'field_id' => $booking->field_id,
            'rating' => $rating,
            'review' => $reviewText,
        ]);

        return back()->with('success', 'Thank you for your feedback! Feedback published successfully.');
    }
}
