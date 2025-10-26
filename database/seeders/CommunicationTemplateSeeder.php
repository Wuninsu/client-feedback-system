<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use App\Models\SmsTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommunicationTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Email Templates
        $emailTemplates = [
            [
                'name' => 'Feedback Received',
                'subject' => 'Thank You for Your Feedback',
                'body' => 'We have received your feedback. Our team will review and respond shortly.',
            ],
            [
                'name' => 'Feedback Response',
                'subject' => 'We’ve Responded to Your Feedback',
                'body' => 'We’ve addressed your concern. Please check your feedback dashboard.',
            ],
        ];

        foreach ($emailTemplates as $email) {
            EmailTemplate::create($email);
        }

        // SMS Templates
        $smsTemplates = [
            [
                'name' => 'Feedback Submitted',
                'body' => 'Thank you! Your feedback has been successfully submitted.',
            ],
            [
                'name' => 'Feedback Replied',
                'body' => 'Your feedback has a new response. Please check it online.',
            ],
        ];

        foreach ($smsTemplates as $sms) {
            SmsTemplate::create($sms);
        }
    }
}
