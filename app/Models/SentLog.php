<?php

namespace App\Models;

use Carbon\Exceptions\Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SentLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'feedback_id',
        'user_id',
        'channel',
        'to',
        'message',
        'status',
        'sent_at',
    ];
    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guestUser()
    {
        return $this->belongsTo(GuestUser::class);
    }


    static public function sendSMS($recipientNumber, $message): bool
    {

        // send sms to only these numbers;
        // if (!in_array($recipientNumber, ['0599678749', '0543870404', '0247125143', '0554234794'])) {
        //     return false;
        // }

        // Define parameters
        $api_key = "Z0NGQ2dFVkpTdnlUY0dvc2N3cEg";
        $from = "NIVENTURES";
        $to = $recipientNumber; // Recipient's phone number
        $msg = urlencode($message); // Encode the message

        // Initialize cURL request
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sms.arkesel.com/sms/api?action=send-sms&api_key=$api_key&to=$to&from=$from&sms=$msg",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        // Execute cURL request
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            // Handle cURL error
            $error_msg = curl_error($curl);
            curl_close($curl);
            // Log or handle the error message
            return false;
        }
        curl_close($curl);

        // Handle the API response
        if ($response) {
            $result = trim($response, '[]');
            $sms_res = json_decode($result);

            if ($sms_res && isset($sms_res->code) && $sms_res->code == 'ok') {
                return true; // SMS sent successfully
            }
        }

        // Default failure case
        return false;
    }


    /**
     * Retrieve all sent SMS messages with user details.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getSentSms()
    {
        return self::where('status', 'sent')
            ->get()
            ->map(function ($sms) {
                return [
                    'message_type' => $sms->message_type,
                    'to' => $sms->recipient_phone,
                    'message' => $sms->message,
                    'sent_at' => $sms->sent_at
                ];
            });
    }


    /**
     * Retrieve all failed SMS messages with user details.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getFailedSms()
    {
        return self::where('status', 'failed')
            ->get()
            ->map(function ($sms) {
                return [
                    'message_type' => $sms->message_type,
                    'to' => $sms->recipient_phone,
                    'message' => $sms->message,
                    'sent_at' => $sms->sent_at
                ];
            });
    }



    public static function deleteAllSent(): int
    {
        return static::where('status', 'sent')->delete();
    }


    /**
     * Retry sending all failed SMS messages.
     *
     * @return array
     */
    public static function retryFailedSms()
    {
        // Fetch all failed SMS messages
        $failedSms = self::where('status', 'failed')->get();

        $results = [
            'retried' => 0,
            'success' => 0,
            'failed' => 0,
        ];

        foreach ($failedSms as $sms) {
            try {
                // send sms
                $isSent = self::sendSms($sms->recipient_phone, $sms->message);

                if ($isSent) {
                    $sms->update([
                        'status' => 'sent',
                        'sent_at' => now(),
                    ]);
                    $results['success']++;
                } else {
                    $results['failed']++;
                }
            } catch (Exception $e) {
                // Log the error
                Log::error('SMS retry failed: ' . $e->getMessage(), [
                    'sms_id' => $sms->id,
                ]);
                $results['failed']++;
            }

            $results['retried']++;
        }
        return $results;
    }


    public static function sendSmsAndLog($record, $messageType, $placeholdersCallback)
    {
        return DB::transaction(function () use ($record, $messageType, $placeholdersCallback) {
            // Generate placeholders dynamically
            $placeholders = $placeholdersCallback($record);
            $smsMessage = generateSmsMessage($messageType, $placeholders);

            try {
                // Attempt to send the SMS
                $status = SentLog::sendSMS($record['phone'], $smsMessage) ? 'sent' : 'failed';

                // Log the result in the database
                SentLog::updateOrCreate(
                    ['to' => $record['phone'], 'message' => $smsMessage, 'sent_at' => now()],
                    ['status' => $status],
                );

                // Commit the transaction explicitly
                DB::commit();

                // Return true if SMS was successfully sent
                return $status;
            } catch (Exception $e) {
                // Rollback on failure
                DB::rollBack();
                return false;
            }
        });
    }
}
