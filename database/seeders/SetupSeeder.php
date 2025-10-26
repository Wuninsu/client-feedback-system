<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $setups = [
            ['key' => 'logo', 'value' => null],
            ['key' => 'business_name', 'value' => 'NI Ventures'],
            ['key' => 'email', 'value' => 'info@niventures.com'],
            ['key' => 'address', 'value' => '123 Main Street, Tamale, Ghana'],
            ['key' => 'support_email', 'value' => 'support@niventures.com'],
            ['key' => 'support_phone', 'value' => '+233501234567'],
            ['key' => 'motto', 'value' => 'Affordable Quality, Everyday.'],
            ['key' => 'facebook_link', 'value' => 'https://facebook.com/niventures'],
            ['key' => 'x_link', 'value' => 'https://x.com/niventures'],
            ['key' => 'instagram_link', 'value' => 'https://instagram.com/niventures'],
            ['key' => 'youtube_link', 'value' => 'https://youtube.com/@niventures'],
            ['key' => 'timezone', 'value' => 'Africa/Accra'],
            ['key' => 'date_format', 'value' => 'Y-m-d'],
            ['key' => 'time_format', 'value' => 'H:i:s'],
            ['key' => 'footer_text', 'value' => 'NI Ventures is a trusted retail enterprise offering a wide range of quality electronics, home appliances, and general goods to meet your everyday needs.'],
            ['key' => 'copy_right_text', 'value' => '© 2025 NI Ventures. All rights reserved.'],

            // About & Retail-focused Company Info
            ['key' => 'about_us', 'value' => 'NI Ventures is a retail enterprise specializing in general goods such as electronics, home appliances, and household items.'],
            ['key' => 'about_us_sub', 'value' => 'We are committed to providing affordable and quality products for every Ghanaian home and business.'],
            ['key' => 'mission_statement', 'value' => 'To provide customers with the best value through quality products, competitive pricing, and reliable customer service.'],
            ['key' => 'vision_statement', 'value' => 'To become Ghana’s most trusted retail brand for electronics and home essentials.'],
            ['key' => 'why_choose_us', 'value' => 'At NI Ventures, we combine affordability, quality, and excellent service to deliver a shopping experience that meets your everyday needs.'],
            ['key' => 'why_choose_us_points', 'value' => json_encode([
                [
                    'title' => 'Wide Product Range',
                    'description' => 'From electronics to home appliances, we stock products that matter most to you.',
                ],
                [
                    'title' => 'Customer Satisfaction',
                    'description' => 'We prioritize your needs and offer support every step of the way.',
                ],
                [
                    'title' => 'Reliable Delivery',
                    'description' => 'Enjoy fast and secure delivery services across Ghana.',
                ],
            ])]
        ];

        foreach ($setups as $setting) {
            DB::table('setups')->updateOrInsert(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
