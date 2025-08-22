<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class EnvController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            return $this->update($request);
        }

        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);
        $envLines = array_filter(explode("\n", $envContent));

        $envVariables = [];
        foreach ($envLines as $line) {
            if (strpos($line, '=') !== false && substr(trim($line), 0, 1) !== '#') {
                [$key, $value] = explode('=', $line, 2);
                $envVariables[$key] = trim($value);
            }
        }

        return view('admin.env.index', compact('envVariables'));
    }

  public function update(Request $request)
    {
        // Only pick the keys that should be updated
        $allowedKeys = [
            'MAIL_HOST',
            'MAIL_PORT',
            'MAIL_USERNAME',
            'MAIL_PASSWORD',
            'MAIL_ENCRYPTION',
            'MAIL_FROM_ADDRESS',
            'MAIL_FROM_NAME',


            'PHONEPE_ENV',
            'PHONEPE_MERCHANT_ID',
            'PHONEPE_CLIENT_ID',
            'PHONEPE_CLIENT_SECRET',
            'PHONEPE_CLIENT_VERSION',

            'PAYPAL_SANDBOX_CLIENT_ID',
            'PAYPAL_SANDBOX_CLIENT_SECRET',
            'PAYPAL_LIVE_CLIENT_ID',
            'PAYPAL_LIVE_CLIENT_SECRET',


            'MAILCHIMP_API_KEY',
            'MAILCHIMP_SERVER_PREFIX',
            'MAILCHIMP_AUDIENCE_ID',

            'VIMEO_CLIENT_ID',
            'VIMEO_CLIENT_SECRET',
            'VIMEO_ACCESS_TOKEN',

            'GEMINI_API_KEY',
            'GEMINI_BASE_URL',
            'GEMINI_REQUEST_TIMEOUT',

        ];
        $data = $request->only($allowedKeys);

        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);
        $envLines = explode("\n", $envContent);

        foreach ($data as $key => $value) {
            $keyFound = false;

            foreach ($envLines as &$line) {

                if (preg_match("/^{$key}=/", $line)) {
                    $line = "{$key}=\"" . addslashes($value) . "\"";
                    $keyFound = true;
                    break;
                }
            }

            if (!$keyFound) {
                $envLines[] = "{$key}=\"" . addslashes($value) . "\"";
            }
        }

        file_put_contents($envPath, implode("\n", $envLines));

        Artisan::call('config:clear');

        return redirect()->route('admin.dashboard')->with('success', 'System Confirgations updated successfully!');
    }
}
