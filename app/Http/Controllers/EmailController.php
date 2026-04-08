<?php

namespace App\Http\Controllers;

use App\Mail\AppMail;
use App\Models\EmailLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    private function getAppConfig(string $appKey): ?array
    {
        if ($appKey === 'sms') {
            return [
                'name' => 'Sodha Marine Services',
                'from_email' => 'info@elevateindustries.co.in',
                'from_name' => 'Sodha Marine Services',
                'to_email' => 'technical@sodhamarine.com',
                'subject_prefix' => 'Contact Form Inquiry',
            ];
        }

        // Add more apps here:
        // else if ($appKey === 'another_app_key') {
        //     return [...];
        // }

        return null;
    }

    public function send(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'app_key' => 'required|string',
            'recaptcha_token' => 'required|string',
            'data' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Verify reCAPTCHA v2 token
        $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->recaptcha_token,
            'remoteip' => $request->ip(),
        ]);

        if (!$recaptchaResponse->json('success')) {
            return response()->json([
                'success' => false,
                'message' => 'reCAPTCHA verification failed. Please try again.',
            ], 422);
        }

        $app = $this->getAppConfig($request->app_key);

        if (!$app) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or inactive app key.',
            ], 401);
        }

        $data = $request->data;
        $subject = $app['subject_prefix'] . ': ' . ($data['subject'] ?? 'New Inquiry');

        try {
            Mail::to($app['to_email'])
                ->send(new AppMail(
                    appConfig: $app,
                    subject: $subject,
                    payload: $data,
                ));

            EmailLog::create([
                'app_key' => $request->app_key,
                'to_email' => $app['to_email'],
                'from_email' => $app['from_email'],
                'subject' => $subject,
                'payload' => $data,
                'status' => 'sent',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully.',
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Email send failed', [
                'app_key' => $request->app_key,
                'error' => $e->getMessage(),
                'file' => $e->getFile() . ':' . $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            EmailLog::create([
                'app_key' => $request->app_key,
                'to_email' => $app['to_email'],
                'from_email' => $app['from_email'],
                'subject' => $subject,
                'payload' => $data,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send email.',
                'debug' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
