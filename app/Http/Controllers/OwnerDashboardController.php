<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Security check
        if (!$user->isOwner()) {
            return redirect()->route('fields.index')->with('error', 'Unauthorized. Athletes command center only.');
        }

        $myFields = Field::where('owner_id', $user->id)->get();
        $myFieldIds = $myFields->pluck('id')->toArray();

        // Recent Activity bookings list
        $recentBookings = Booking::whereIn('field_id', $myFieldIds)
            ->with(['field', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        return view('owner.dashboard', compact('myFields', 'recentBookings'));
    }

    /**
     * API: Return daily revenue and bookings data for chart.
     */
    public function chartData(Request $request)
    {
        $user = Auth::user();

        if (!$user->isOwner()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $year = (int) $request->input('year', Carbon::now()->year);
        $month = (int) $request->input('month', Carbon::now()->month);

        $myFieldIds = Field::where('owner_id', $user->id)->pluck('id')->toArray();

        $bookingsRaw = Booking::whereIn('field_id', $myFieldIds)
            ->where('status', 'confirmed')
            ->whereMonth('booking_date', $month)
            ->whereYear('booking_date', $year)
            ->selectRaw('booking_date, SUM(total_price) as daily_revenue, COUNT(*) as daily_bookings')
            ->groupBy('booking_date')
            ->get()
            ->keyBy(fn($item) => Carbon::parse($item->booking_date)->format('Y-m-d'));

        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        
        $labels = [];
        $revenue = [];
        $bookings = [];

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $d);
            $labels[] = sprintf('%02d %s', $d, Carbon::createFromDate($year, $month, 1)->format('M'));
            
            if (isset($bookingsRaw[$date])) {
                $revenue[] = (float) $bookingsRaw[$date]->daily_revenue;
                $bookings[] = (int) $bookingsRaw[$date]->daily_bookings;
            } else {
                $revenue[] = 0.0;
                $bookings[] = 0;
            }
        }

        return response()->json([
            'year' => $year,
            'month' => $month,
            'labels' => $labels,
            'revenue' => $revenue,
            'bookings' => $bookings,
        ]);
    }

    /**
     * API: Return booking counts per date for the calendar.
     */
    public function calendarBookings(Request $request)
    {
        $user = Auth::user();

        if (!$user->isOwner()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $year = (int) $request->input('year', Carbon::now()->year);
        $month = (int) $request->input('month', Carbon::now()->month);
        $fieldId = $request->input('field_id');

        $myFieldIds = Field::where('owner_id', $user->id)->pluck('id')->toArray();

        // If field_id is given, ensure it belongs to this owner
        if ($fieldId && !in_array((int) $fieldId, $myFieldIds)) {
            return response()->json(['error' => 'Field not found'], 404);
        }

        $targetFieldIds = $fieldId ? [(int) $fieldId] : $myFieldIds;

        $bookings = Booking::whereIn('field_id', $targetFieldIds)
            ->where('status', 'confirmed')
            ->whereMonth('booking_date', $month)
            ->whereYear('booking_date', $year)
            ->selectRaw('booking_date, COUNT(*) as booking_count')
            ->groupBy('booking_date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->booking_date)->format('Y-m-d') => $item->booking_count];
            });

        return response()->json([
            'year' => $year,
            'month' => $month,
            'bookings' => $bookings,
        ]);
    }

    public function createField()
    {
        if (!Auth::user()->isOwner()) {
            return redirect()->route('fields.index')->with('error', 'Akses ditolak. Halaman ini hanya untuk owner.');
        }
        return view('owner.fields.create');
    }

    public function storeField(Request $request)
    {
        if (!Auth::user()->isOwner()) {
            return redirect()->route('fields.index')->with('error', 'Akses ditolak. Halaman ini hanya untuk owner.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'sport_type' => 'required|in:padel,tennis,badminton',
            'price_per_hour' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'is_indoor' => 'required|boolean',
            'features' => 'nullable|string', // comma separated values
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'required|in:active,maintenance,cleaning',
        ]);

        // Parse features string into array
        $featuresArray = [];
        if ($request->filled('features')) {
            $featuresArray = array_map('trim', explode(',', $request->features));
        }

        // Store multiple images
        $imageUrls = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('fields', 'public');
                $imageUrls[] = '/storage/' . $path;
            }
        }

        // Default fallback if no images uploaded
        if (empty($imageUrls)) {
            $imageUrls[] = 'https://images.unsplash.com/photo-1545809074-59472b3f5eca';
        }

        Auth::user()->fields()->create([
            'name' => $request->name,
            'sport_type' => $request->sport_type,
            'price_per_hour' => $request->price_per_hour,
            'capacity' => $request->capacity,
            'is_indoor' => (bool)$request->is_indoor,
            'features' => $featuresArray,
            'description' => $request->description,
            'location' => $request->location,
            'image_url' => $imageUrls, // Will be cast to JSON string by mutator
            'status' => $request->status,
        ]);

        return redirect()->route('owner.dashboard')
            ->with('success', 'Field created successfully and integrated to raketinAJA.');
    }

    public function editField(Field $field)
    {
        // Security check
        if (Auth::id() !== $field->owner_id) {
            return redirect()->route('owner.dashboard')->with('error', 'Unauthorized access.');
        }

        return view('owner.fields.edit', compact('field'));
    }

    public function updateField(Request $request, Field $field)
    {
        // Security check
        if (Auth::id() !== $field->owner_id) {
            return redirect()->route('owner.dashboard')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'sport_type' => 'required|in:padel,tennis,badminton',
            'price_per_hour' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'is_indoor' => 'required|boolean',
            'features' => 'nullable|string', // comma separated values
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'deleted_images' => 'nullable|array',
            'status' => 'required|in:active,maintenance,cleaning',
        ]);

        // Parse features string into array
        $featuresArray = [];
        if ($request->filled('features')) {
            $featuresArray = array_map('trim', explode(',', $request->features));
        }

        // Get current images list
        $currentImages = is_array($field->image_url) ? $field->image_url : [$field->image_url];

        // Process deleted images
        $deletedImages = $request->input('deleted_images', []);
        foreach ($deletedImages as $deletedUrl) {
            if (($key = array_search($deletedUrl, $currentImages)) !== false) {
                unset($currentImages[$key]);
            }
            // Delete file from local storage if applicable
            if (strpos($deletedUrl, '/storage/') === 0) {
                $filePath = str_replace('/storage/', '', $deletedUrl);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($filePath);
            }
        }

        // Process new uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('fields', 'public');
                $currentImages[] = '/storage/' . $path;
            }
        }

        $currentImages = array_values($currentImages);

        // Fallback default image if all images are deleted
        if (empty($currentImages)) {
            $currentImages[] = 'https://images.unsplash.com/photo-1545809074-59472b3f5eca';
        }

        $field->update([
            'name' => $request->name,
            'sport_type' => $request->sport_type,
            'price_per_hour' => $request->price_per_hour,
            'capacity' => $request->capacity,
            'is_indoor' => (bool)$request->is_indoor,
            'features' => $featuresArray,
            'description' => $request->description,
            'location' => $request->location,
            'image_url' => $currentImages, // Will be cast to JSON string by mutator
            'status' => $request->status,
        ]);

        return redirect()->route('owner.dashboard')
            ->with('success', 'Field configuration updated.');
    }

    public function destroyField(Field $field)
    {
        // Security check
        if (Auth::id() !== $field->owner_id) {
            return redirect()->route('owner.dashboard')->with('error', 'Unauthorized access.');
        }

        // Delete associated files from storage
        $images = is_array($field->image_url) ? $field->image_url : [$field->image_url];
        foreach ($images as $imageUrl) {
            if (strpos($imageUrl, '/storage/') === 0) {
                $filePath = str_replace('/storage/', '', $imageUrl);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($filePath);
            }
        }

        $field->delete();

        return redirect()->route('owner.dashboard')
            ->with('success', 'Field removed from the system registry.');
    }
}
