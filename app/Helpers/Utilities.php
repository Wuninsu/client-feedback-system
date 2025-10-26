<?php

use App\Models\ActivityLog;
use App\Models\OrdersModel;
use App\Models\ProductsModel;
use App\Models\settingsModel;
use App\Models\Setup;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
function generateUsername($name)
{
    // Step 1: Clean the name (remove spaces, make it lowercase)
    $baseUsername = strtolower(str_replace(' ', '', $name));  // Remove spaces and make lowercase

    // Step 2: Shorten the username to the first 6 characters of the cleaned name
    $baseUsername = substr($baseUsername, 0, 6);  // Limit to first 6 characters

    // Step 3: Generate random numbers and letters
    $randomNumber = rand(100, 9999);  // Random 3 to 4 digit number
    $randomChar = chr(rand(97, 122)); // Random lowercase letter (a-z)

    // Step 4: Create the base username
    $username = $baseUsername . $randomNumber . $randomChar;

    // Step 5: Ensure the username is unique
    // Check if the username already exists in the database
    while (User::where('username', $username)->exists()) {
        // If the username exists, regenerate the random part and try again
        $randomNumber = rand(100, 9999);  // New random 3 to 4 digit number
        $randomChar = chr(rand(97, 122)); // New random lowercase letter
        $username = $baseUsername . $randomNumber . $randomChar;
    }

    return $username;
}
if (!function_exists('setupData')) {

    function logActivity(string $action, ?\Illuminate\Database\Eloquent\Model $model = null, ?int $userId = null): void
    {
        ActivityLog::create([
            'user_id' => $userId ?? auth('web')->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
if (!function_exists('setupData')) {
    function setupData($key): ?string
    {
        $setup = \App\Models\Setup::setupData();
        return $setup[$key] ?? '';
    }
}
if (!function_exists('generateSmsMessage')) {
    /**
     * Generate an SMS message from a template and replace placeholders.
     *
     * @param string $templateKey The key or identifier of the template.
     * @param array $data The data to replace in the template.
     * @return string|null The generated SMS message or null if template not found.
     */
    function generateSmsMessage(string $templateKey, array $data): ?string
    {
        $template = \App\Models\SmsTemplate::where('name', $templateKey)->first();

        if (!$template) {
            return false;
        }
        $message = $template->template;

        // Replace placeholders with data
        foreach ($data as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }

        return $message;
    }
}

if (!function_exists('uploadFile')) {
    /**
     * Upload and rename a file dynamically.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory
     * @param string|null $customName
     * @return string The path of the uploaded file.
     */


    function uploadFile($file, $directory = 'uploads', $customName = null)
    {
        // Ensure directory exists
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Generate a unique name if a custom name isn't provided
        $extension = $file->getClientOriginalExtension();
        $filename = $customName
            ? Str::slug($customName) . '.' . $extension
            : Str::uuid()->toString() . '.' . $extension;

        // Upload the file to the public disk
        $path = $file->storeAs($directory, $filename, 'public');

        // Return the public URL
        return Storage::url($path); // e.g., /storage/uploads/abc.jpg
    }
}


if (!function_exists('paginationLimit')) {
    function paginationLimit()
    {
        $settings = Setup::setupData();
        return $settings['pagination_limit'] ?? 7;
    }
}



if (!function_exists('intWithStyle')) {
    /** Style the number value with a suffix like 10M or 10K or 10.3B */
    function intWithStyle($n)
    {
        if ($n < 1000)
            return $n;
        $suffix = ['', 'K', 'M', 'B', 'T', 'P', 'E', 'Z', 'Y'];
        $power = floor(log($n, 1000));
        return round($n / (1000 ** $power), 1, PHP_ROUND_HALF_EVEN) . $suffix[$power];
    }
}


// if (!function_exists('companyData')) {
//     function companyData()
//     {
//         return settingsModel::getSettingsData();
//     }
// }


if (!function_exists('sendSMS')) {
    /**
     * Summary of sendSMS
     * @param array $data
     * @return bool
     */
    function sendSMS($data): bool
    {
        // Define parameters
        $api_key = "T1dKd3F3R3p3YWdLb3ZzZWtxSXM";
        $from = "NIVentures";
        $to = $data['phone']; // Recipient's phone number
        $msg = urlencode($data['message']); // Encode the message

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
}

if (!function_exists('getLowStockProducts')) {
    function getLowStockProducts($threshold = 100)
    {
        $settings = ModelsSettingsModel::getSettingsData();
        $lowStock = $settings['low_stock'] ?? 100;
        return $products = ProductsModel::where('stock', '>', 0)
            ->where('stock', '<=', $lowStock)
            ->get();
    }
}

if (!function_exists(' getOutOfStockProducts')) {
    function getOutOfStockProducts()
    {
        return ProductsModel::where('stock', '=', 0)->get();
    }
}
