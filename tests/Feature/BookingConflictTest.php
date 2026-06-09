<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Field;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingConflictTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private User $player;
    private Field $field;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::create([
            'name' => 'Owner User',
            'email' => 'owner@example.com',
            'phone' => '111',
            'role' => 'owner',
            'password' => bcrypt('password'),
        ]);

        $this->player = User::create([
            'name' => 'Player User',
            'email' => 'player@example.com',
            'phone' => '222',
            'role' => 'player',
            'password' => bcrypt('password'),
        ]);

        $this->field = Field::create([
            'owner_id' => $this->owner->id,
            'name' => 'Apex Padel Center',
            'sport_type' => 'padel',
            'price_per_hour' => 45.00,
            'capacity' => 4,
            'is_indoor' => true,
            'features' => ['Indoor', 'Pro-Turf'],
            'location' => 'Chamartín, Madrid',
            'image_url' => 'https://example.com/field.jpg',
            'status' => 'active',
        ]);
    }

    public function test_player_can_book_available_slot(): void
    {
        $response = $this->actingAs($this->player)->post('/bookings', [
            'field_id' => $this->field->id,
            'date' => '2026-06-15',
            'start_time' => '10:00:00',
            'end_time' => '11:30:00',
            'total_price' => 67.50,
        ]);

        $response->assertRedirect('/bookings');
        $this->assertDatabaseHas('bookings', [
            'field_id' => $this->field->id,
            'booking_date' => '2026-06-15 00:00:00',
            'start_time' => '10:00:00',
            'end_time' => '11:30:00',
        ]);
    }

    public function test_double_booking_is_prevented_on_exact_overlap(): void
    {
        // First booking
        Booking::create([
            'user_id' => $this->player->id,
            'field_id' => $this->field->id,
            'booking_date' => '2026-06-15',
            'start_time' => '10:00:00',
            'end_time' => '11:30:00',
            'total_price' => 67.50,
            'status' => 'confirmed',
        ]);

        // Second booking attempt at exact same time
        $response = $this->actingAs($this->player)->post('/bookings', [
            'field_id' => $this->field->id,
            'date' => '2026-06-15',
            'start_time' => '10:00:00',
            'end_time' => '11:30:00',
            'total_price' => 67.50,
        ]);

        // Should redirect back to field details with error
        $response->assertRedirect('/fields/' . $this->field->id);
        $response->assertSessionHas('error');
        
        // Assert only 1 booking exists in database
        $this->assertEquals(1, Booking::count());
    }

    public function test_double_booking_is_prevented_on_partial_overlap_start(): void
    {
        // First booking: 10:00 - 11:30
        Booking::create([
            'user_id' => $this->player->id,
            'field_id' => $this->field->id,
            'booking_date' => '2026-06-15',
            'start_time' => '10:00:00',
            'end_time' => '11:30:00',
            'total_price' => 67.50,
            'status' => 'confirmed',
        ]);

        // Attempt overlap starting at 09:30 - 11:00 (overlaps from 10:00 to 11:00)
        $response = $this->actingAs($this->player)->post('/bookings', [
            'field_id' => $this->field->id,
            'date' => '2026-06-15',
            'start_time' => '09:30:00',
            'end_time' => '11:00:00',
            'total_price' => 67.50,
        ]);

        $response->assertRedirect('/fields/' . $this->field->id);
        $this->assertEquals(1, Booking::count());
    }

    public function test_double_booking_is_prevented_on_partial_overlap_end(): void
    {
        // First booking: 10:00 - 11:30
        Booking::create([
            'user_id' => $this->player->id,
            'field_id' => $this->field->id,
            'booking_date' => '2026-06-15',
            'start_time' => '10:00:00',
            'end_time' => '11:30:00',
            'total_price' => 67.50,
            'status' => 'confirmed',
        ]);

        // Attempt overlap starting at 11:00 - 12:30 (overlaps from 11:00 to 11:30)
        $response = $this->actingAs($this->player)->post('/bookings', [
            'field_id' => $this->field->id,
            'date' => '2026-06-15',
            'start_time' => '11:00:00',
            'end_time' => '12:30:00',
            'total_price' => 67.50,
        ]);

        $response->assertRedirect('/fields/' . $this->field->id);
        $this->assertEquals(1, Booking::count());
    }

    public function test_non_overlapping_booking_on_same_day_succeeds(): void
    {
        // First booking: 10:00 - 11:30
        Booking::create([
            'user_id' => $this->player->id,
            'field_id' => $this->field->id,
            'booking_date' => '2026-06-15',
            'start_time' => '10:00:00',
            'end_time' => '11:30:00',
            'total_price' => 67.50,
            'status' => 'confirmed',
        ]);

        // Second booking immediately after: 11:30 - 13:00 (no overlap)
        $response = $this->actingAs($this->player)->post('/bookings', [
            'field_id' => $this->field->id,
            'date' => '2026-06-15',
            'start_time' => '11:30:00',
            'end_time' => '13:00:00',
            'total_price' => 67.50,
        ]);

        $response->assertRedirect('/bookings');
        $this->assertEquals(2, Booking::count());
    }
}
