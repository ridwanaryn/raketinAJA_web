<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Field;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Owner User
        $owner = User::create([
            'name' => 'Marcus Thorne',
            'email' => 'marcus@raketinAJA.com',
            'phone' => '+1 (555) 123-4567',
            'role' => 'owner',
            'password' => Hash::make('password'),
        ]);

        // 2. Create Player User
        $player = User::create([
            'name' => 'Alex Rivera',
            'email' => 'alex@raketinAJA.com',
            'phone' => '+1 (555) 765-4321',
            'role' => 'player',
            'password' => Hash::make('password'),
        ]);

        // Create secondary testing player
        $player2 = User::create([
            'name' => 'John Doe',
            'email' => 'player@example.com',
            'phone' => '+1 (555) 999-8888',
            'role' => 'player',
            'password' => Hash::make('password'),
        ]);

        // 3. Create Fields
        $fieldsData = [
            [
                'name' => 'Apex Padel Center',
                'sport_type' => 'padel',
                'price_per_hour' => 45.00,
                'capacity' => 4,
                'is_indoor' => true,
                'features' => ['Indoor', 'Pro-Turf', 'Showers', 'Night Lights'],
                'description' => 'Elite indoor padel arena with blue turf, glass walls, and bright professional stadium lighting.',
                'location' => 'Chamartín, Madrid',
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBrm3ei7dGJaSUfJOZ1EQz_8zS_t1X3miNBn8pkVqEnD8ScqDhylxZms6iya5Uz3caoJfJcz5FtaZ77ncDNr-n6clfC1YntAaUUpHeBn6OA9Dym35Gur7aGARZStp4d2GZPUKOBfwVFle3GxX-21i_AgcOJipsiG_UU-LwnhRsJqgZOPRXHiFscfJZOm_KCm06JH3KNXfO95OFOXx9vIX_dBUd37mO1qFxQwNRvvwDcxtN2S8FB3SGWGGyWwZb0z_z8zyIYE7VCnA',
                'status' => 'active',
            ],
            [
                'name' => 'The Royal Grass Club',
                'sport_type' => 'tennis',
                'price_per_hour' => 60.00,
                'capacity' => 4,
                'is_indoor' => false,
                'features' => ['Outdoor', 'Cafe Access', 'Pro-Turf', 'Night Lights'],
                'description' => 'Prestigious red clay tennis courts surrounded by lush green landscapes, offering perfect sunlight play.',
                'location' => 'Salamanca, Madrid',
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDxYrQspsOzVuXx-t2ZxiFakKuY6bcGUdTV-6_52mQMzT4tqIN-c3go2RNwtFJxJE04VQHnsJsGFxEOmZ6Rhg7NBvQH-kDVcT3udjL6seRFzHFxblwDyu2IojnKazYOW-gIQy4soeCqrNuIVV1_i7eKMC9JbdG2fRbtcsxbqo5xNPJYD9T9UVpYgCzUOuM-Y1cfoEnNMavZP9Zwa4hXSvEXn0WPPeac8I1KLF_-vkYD0mS67iuvRIverOFsQAJKc2sv5GFYM9MJGA',
                'status' => 'active',
            ],
            [
                'name' => 'Velocity Sports Hall',
                'sport_type' => 'badminton',
                'price_per_hour' => 30.00,
                'capacity' => 4,
                'is_indoor' => true,
                'features' => ['Multi-sport', 'Locker Rooms', 'Showers', 'Night Lights'],
                'description' => 'Spacious sports hall featuring multiple premium badminton courts with shock-absorbent flooring.',
                'location' => 'Tetuán, Madrid',
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBvDXz1-96JVcE5N6SJdnfEUXN4nsM5xvioh_h4Ybmp3NBTVUEQZssEK8AwEbZr_v9-FPuCusse-a4eVL7KgjrVmfgN-eIdB0ge83P_o8txe6uc15dU8k-eH3pWkTI4_Ix8SsFJqTG2GQQosmxP4SEaHiHJQg-vzgZeuYEdjv12TlWzjx5IFdE9d6N5ZDYXsQgajHpuoKnF2mCu_bWFASPlX8_wLukEOYxu3VO3w9u3geobWhum4Nv_mIAuog_5nGHRiKxENSBAoA',
                'status' => 'active',
            ],
            [
                'name' => 'Urban Padel Hub',
                'sport_type' => 'padel',
                'price_per_hour' => 38.00,
                'capacity' => 4,
                'is_indoor' => false,
                'features' => ['24/7 Access', 'Parking', 'Showers', 'Night Lights'],
                'description' => 'Vibrant outdoor padel courts with high-quality artificial turf, accessible at any time of day.',
                'location' => 'Arganzuela, Madrid',
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDX0PXrrrFOFDPJ-NvqDHELTK1OIKVTnyS-P4wgRe4BkWRSklr6BR3kIpPlOtGzjovfEsSd6S29laUBTOCiCdKkxlNCBM75QDnqWGqY4z7TWayeQPF9UgcZ9on8JlCYGczS92w2qQ0fApGVtQfQHaGuU3a-ZTQ9OSBNZwzwHoP7VMSPNmhM1mKbJg8LqCI8KyoikoREz36FfgR8s1ui-2goIb5bkDRteiAXXq_yLcR4ZO3tY2PLSF6jXQ1bExSEzxF-9s3BVBWUng',
                'status' => 'active',
            ],
            [
                'name' => 'Main Pitch Alpha',
                'sport_type' => 'padel',
                'price_per_hour' => 40.00,
                'capacity' => 4,
                'is_indoor' => false,
                'features' => ['Pro-Turf', 'Showers', 'Night Lights'],
                'description' => 'Main premium outdoor padel court under bright stadium lights.',
                'location' => 'Chamartín, Madrid',
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCG8b0flEaZ_aU2P5c6A-h36BY6LhBPp1ppktSebDnGuQSdHkJbjTlf6ib9zy9LUYd9i4t_YtwUf91DJyMGRPGYBQwgtcCnxbCbDkEKWjVJ_V4Ciokxbt2uXiJWvwRVSYnU1b7ST6YKebB9Wffyz0icscXs_KZ8ff9UtQ_PWgj8fxpqKRU9vF96RuhTzatWzW8iY3DmK2H6mVkgFL8KrisRmj5JLB-zRe6etARvew0FwhrNKU5xgupabSRnY91EpUL-FnGxtIu_kQ',
                'status' => 'active',
            ],
            [
                'name' => 'The Cage (Indoors)',
                'sport_type' => 'tennis',
                'price_per_hour' => 55.00,
                'capacity' => 4,
                'is_indoor' => true,
                'features' => ['Indoor', 'Pro-Turf', 'Locker Rooms'],
                'description' => 'Indoor high-intensity court with premium sound isolation and grip turf.',
                'location' => 'Salamanca, Madrid',
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDFydxkoyNKOYskTDIhQGUag4YULvuva4unpRswr-ORFwl8p-Sr8IhemEsmrHcuGOrxzxVmykH-nh9ETCej-j9v8xuzOmXfwTtQIKOeJsGFKma09QgPKyMI3MgBdN9lrbCEYaj50KWnwcN_hqaKDv5m0fOH-DKXI4bX0rTmq88oPQwQjxeLfUCJS-2xuaW7NgC9UVTrjFkSlbEOD9gcyadoyx10vf7IAZDe0-rZG6kz-EokK-YKTPuAKKFhpCCUEgB83OFaDIQ5jg',
                'status' => 'cleaning',
            ],
            [
                'name' => 'Court West',
                'sport_type' => 'badminton',
                'price_per_hour' => 35.00,
                'capacity' => 2,
                'is_indoor' => false,
                'features' => ['Outdoor', 'Showers', 'Night Lights'],
                'description' => 'High-grade outdoor badminton court ideal for casual play or intense rallies.',
                'location' => 'Tetuán, Madrid',
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDFwqA_cLwNlShOpf_7BB-2WhJGYmY7odRR-VMwZHJ8M1Ne7JZFbPnaHtMdpdulrLQxyuOXu8SQNkFtL7VCxrPdFlh_ZF-D2R6ZQJhAv6ZdNyBndPD4LyI6QpODVELCKeZTclaEFlSBCeTN6doDuWgtaQBiqUTOTxFzz8KZlrE2n6brMJ6z8Nx1pFpDZZyFk3vljhQ5DbS0bXCknEYhHbu4DPo_lca-6DgVNsSuP9qB9Kv8jUceT0KxNNQmuGW7F_gMENeOshgKrw',
                'status' => 'active',
            ],
        ];

        $fields = [];
        foreach ($fieldsData as $fd) {
            $fields[] = $owner->fields()->create($fd);
        }

        // 4. Create Bookings & Reviews
        // Past completed booking for Alex Rivera so he can review it
        $pastBooking = Booking::create([
            'user_id' => $player->id,
            'field_id' => $fields[0]->id, // Apex Padel Center
            'booking_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'total_price' => 90.00, // 2 hours
            'status' => 'confirmed',
        ]);

        // Seed some reviews for courts
        Review::create([
            'booking_id' => $pastBooking->id,
            'user_id' => $player->id,
            'field_id' => $fields[0]->id,
            'rating' => 4.9,
            'review' => 'Incredible indoor padel court! The blue turf is clean, and the lighting is perfect.',
        ]);

        Review::create([
            'booking_id' => Booking::create([
                'user_id' => $player2->id,
                'field_id' => $fields[1]->id, // The Royal Grass Club
                'booking_date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'total_price' => 120.00,
                'status' => 'confirmed',
            ])->id,
            'user_id' => $player2->id,
            'field_id' => $fields[1]->id,
            'rating' => 5.0,
            'review' => 'Beautiful red clay courts. The atmosphere here is premium.',
        ]);

        Review::create([
            'booking_id' => Booking::create([
                'user_id' => $player2->id,
                'field_id' => $fields[2]->id, // Velocity Sports Hall
                'booking_date' => Carbon::now()->subDays(4)->format('Y-m-d'),
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'total_price' => 60.00,
                'status' => 'confirmed',
            ])->id,
            'user_id' => $player2->id,
            'field_id' => $fields[2]->id,
            'rating' => 4.7,
            'review' => 'Great floor bounce. Clean lockers and showers.',
        ]);

        Review::create([
            'booking_id' => Booking::create([
                'user_id' => $player2->id,
                'field_id' => $fields[3]->id, // Urban Padel Hub
                'booking_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'start_time' => '19:00:00',
                'end_time' => '21:00:00',
                'total_price' => 76.00,
                'status' => 'confirmed',
            ])->id,
            'user_id' => $player2->id,
            'field_id' => $fields[3]->id,
            'rating' => 4.8,
            'review' => 'Nice outdoor arena with great dusk lighting.',
        ]);

        // Future bookings
        // A booking for Alex Rivera today
        Booking::create([
            'user_id' => $player->id,
            'field_id' => $fields[0]->id, // Apex Padel Center
            'booking_date' => Carbon::now()->format('Y-m-d'),
            'start_time' => '13:00:00',
            'end_time' => '15:00:00',
            'total_price' => 90.00,
            'status' => 'confirmed',
        ]);

        // Active booking for Marcus Thorne dashboard schedule (today)
        Booking::create([
            'user_id' => $player2->id,
            'field_id' => $fields[4]->id, // Main Pitch Alpha
            'booking_date' => Carbon::now()->format('Y-m-d'),
            'start_time' => '18:00:00',
            'end_time' => '20:00:00',
            'total_price' => 80.00,
            'status' => 'confirmed',
        ]);

        // Generate additional completed bookings this month to simulate Owner Dashboard revenue ($24,850 total)
        for ($i = 0; $i < 30; $i++) {
            $randomField = $fields[array_rand($fields)];
            $hours = rand(1, 3);
            Booking::create([
                'user_id' => $player2->id,
                'field_id' => $randomField->id,
                'booking_date' => Carbon::now()->startOfMonth()->addDays(rand(0, 20))->format('Y-m-d'),
                'start_time' => sprintf('%02d:00:00', rand(8, 20)),
                'end_time' => sprintf('%02d:00:00', rand(9, 22)),
                'total_price' => $randomField->price_per_hour * $hours,
                'status' => 'confirmed',
                'created_at' => Carbon::now()->subDays(rand(0, 10)),
            ]);
        }
    }
}
