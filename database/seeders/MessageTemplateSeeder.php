<?php

namespace Database\Seeders;

use App\Models\MessageTemplate;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            // Feedback Request
            [
                'name' => 'client_feedback_request_sms',
                'type' => 'sms',
                'subject' => null,
                'body' => 'Dear customer, we value your opinion! Please take a moment to share your feedback on our product. Click here to respond. – NI Ventures',
            ],
            [
                'name' => 'client_feedback_request_email',
                'type' => 'email',
                'subject' => 'We Value Your Feedback',
                'body' => '<p>Dear customer,</p><p>We at <strong>NI Ventures</strong> value your opinion. Please take a moment to share your feedback on your recent experience with our product. Your input helps us serve you better.</p><p><a href="#">Click here to respond</a></p><p>Thank you,<br>NI Ventures</p>',
            ],

            // Acknowledgment
            [
                'name' => 'client_feedback_acknowledgment_sms',
                'type' => 'sms',
                'subject' => null,
                'body' => 'Thank you for your feedback. We value your input and will address your concerns as soon as possible. – NI Ventures',
            ],
            [
                'name' => 'client_feedback_acknowledgment_email',
                'type' => 'email',
                'subject' => 'Thank You for Your Feedback',
                'body' => '<p>Dear customer,</p><p>Thank you for sharing your feedback. We value your input and are committed to resolving any concerns promptly.</p><p>Warm regards,<br>NI Ventures Support Team</p>',
            ],

            // Resolved
            [
                'name' => 'client_feedback_resolved_sms',
                'type' => 'sms',
                'subject' => null,
                'body' => 'We’re pleased to inform you that your feedback has been reviewed and resolved. Thank you for helping us improve. – NI Ventures',
            ],
            [
                'name' => 'client_feedback_resolved_email',
                'type' => 'email',
                'subject' => 'Your Feedback Has Been Resolved',
                'body' => '<p>Hello,</p><p>We’re pleased to inform you that your feedback has been resolved. Thank you for taking the time to help us grow and improve.</p><p>Kind regards,<br>NI Ventures</p>',
            ],

            // Follow-up
            [
                'name' => 'client_feedback_followup_sms',
                'type' => 'sms',
                'subject' => null,
                'body' => 'We’re following up on your recent feedback. Our team is working on a resolution and will update you soon. – NI Ventures',
            ],
            [
                'name' => 'client_feedback_followup_email',
                'type' => 'email',
                'subject' => 'Follow-Up on Your Feedback',
                'body' => '<p>Hello,</p><p>We’re following up regarding your recent feedback. Our team is actively reviewing it and will update you shortly.</p><p>Thanks again for your input,<br>NI Ventures</p>',
            ],

            // Compensation Offer
            [
                'name' => 'client_feedback_compensation_offer_sms',
                'type' => 'sms',
                'subject' => null,
                'body' => 'We sincerely apologize for the inconvenience. As appreciation, we’re offering a special discount. Visit us to redeem it. – NI Ventures',
            ],
            [
                'name' => 'client_feedback_compensation_offer_email',
                'type' => 'email',
                'subject' => 'We Value You – A Token of Apology',
                'body' => '<p>Dear customer,</p><p>We apologize for any inconvenience caused. As a token of appreciation, please enjoy a special discount on your next visit.</p><p>Thank you for choosing NI Ventures.</p>',
            ],

            // Thank You
            [
                'name' => 'client_feedback_thank_you_sms',
                'type' => 'sms',
                'subject' => null,
                'body' => 'Thank you for your valuable feedback. Your insights help us improve our services. – NI Ventures',
            ],
            [
                'name' => 'client_feedback_thank_you_email',
                'type' => 'email',
                'subject' => 'Thank You for Your Insight',
                'body' => '<p>Dear customer,</p><p>Thank you for your valuable feedback. We appreciate your support and are always working to improve our service.</p><p>Sincerely,<br>NI Ventures Team</p>',
            ],
        ];

        foreach ($templates as $template) {
            MessageTemplate::create($template);
        }
        // foreach ($templates as $template) {
        //     DB::table('message_templates')->updateOrInsert(
        //         ['name' => $template['name']],
        //         [
        //             'type' => $template['type'],
        //             'subject' => $template['subject'],
        //             'body' => $template['body'],
        //             'is_active' => true,
        //             'created_at' => Carbon::now(),
        //             'updated_at' => Carbon::now(),
        //         ]
        //     );
        // }
    }
}
