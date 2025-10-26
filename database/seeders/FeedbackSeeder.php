<?php

namespace Database\Seeders;

use App\Models\Feedback;
use App\Models\FeedbackCategory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Build Quality',
            'Performance',
            'Design & Appearance',
            'Ease of Use',
            'Durability',
            'Functionality',
            'Compatibility',
            'Packaging',
            'Instructions or Manual',
            'Satisfaction',
            'Others'
        ];


        foreach ($categories as $categoryName) {
            $category = FeedbackCategory::create(['name' => $categoryName]);

            // Create dummy feedbacks under each category
            // for ($i = 1; $i <= 2; $i++) {
            //     Feedback::create([
            //         'user_id' => User::inRandomOrder()->value('id'),
            //         'feedback_category_id' => $category->id,
            //         'message' => "Sample feedback #$i for $categoryName",
            //         'is_resolved' => false,
            //     ]);
            // }
        }
    }
}
