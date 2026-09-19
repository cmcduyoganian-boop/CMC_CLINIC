<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected string $provider;
    protected array $config;

    public function __construct()
    {
        $this->provider = config('sms.provider', 'log');
        $this->config = config('sms.providers.' . $this->provider, []);
    }

    /**
     * Send SMS to a phone number
     */
    public function send(string $phone, string $message, array $metadata = []): array
    {
        // Normalize phone number
        $phone = $this->normalizePhone($phone);

        if (!$phone) {
            return ['success' => false, 'error' => 'Invalid phone number'];
        }

        $result = match ($this->provider) {
            'semaphore' => $this->sendViaSemaphore($phone, $message),
            'android' => $this->sendViaAndroid($phone, $message),
            'log', default => $this->sendViaLog($phone, $message, $metadata),
        };

        Log::info('SMS sent', array_merge([
            'phone' => $phone,
            'message' => $message,
            'provider' => $this->provider,
            'success' => $result['success'] ?? false,
        ], $result));

        return $result;
    }

    /**
     * Send appointment reminder SMS
     */
    public function sendAppointmentReminder(Appointment $appointment): array
    {
        $patient = $appointment->patient;

        if (!$patient || !$patient->phone) {
            return ['success' => false, 'error' => 'No patient phone number'];
        }

        $message = $this->buildAppointmentReminderMessage($appointment);

        $result = $this->send($patient->phone, $message, [
            'type' => 'appointment_reminder',
            'appointment_id' => $appointment->id,
            'patient_id' => $patient->id,
        ]);

        // Update appointment SMS status
        $appointment->update([
            'sms_status' => $result['success'] ? 'sent' : 'failed',
            'sms_sent_at' => $result['success'] ? now() : null,
        ]);

        return $result;
    }

    public function sendAccountApproval(User $user): array
    {
        if (!$user->phone) {
            return ['success' => false, 'error' => 'No user phone number'];
        }

        $clinicName = config('app.name', 'CMC Clinic');
        $message = "Dear {$user->name}, your {$clinicName} account has been approved. You may now log in. - {$clinicName}";

        return $this->send($user->phone, $message, [
            'type' => 'account_approval',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Build appointment reminder message
     */
    protected function buildAppointmentReminderMessage(Appointment $appointment): string
    {
        $patientName = $appointment->patient?->name ?? 'Patient';
        $date = $appointment->appointment_date?->format('F j, Y') ?? 'the scheduled date';
        $time = $appointment->appointment_time ? Carbon::parse($appointment->appointment_time)->format('g:i A') : '';
        $clinicName = config('app.name', 'CMC Clinic');

        $template = "Dear {$patientName}, this is a reminder of your clinic appointment on {$date}";
        if ($time) {
            $template .= " at {$time}";
        }
        $template .= ". Please arrive 10 minutes early. - {$clinicName}";

        return $template;
    }

    /**
     * Send via log (development/testing)
     */
    protected function sendViaLog(string $phone, string $message, array $metadata = []): array
    {
        Log::channel('sms')->info('SMS Notification', [
            'to' => $phone,
            'message' => $message,
            'metadata' => $metadata,
        ]);

        return ['success' => true, 'provider' => 'log', 'message_id' => 'log_' . uniqid()];
    }

    /**
     * Send via Android SMS Gateway
     */
    protected function sendViaAndroid(string $phone, string $message): array
    {
        try {
            $url = $this->config['url'] ?? env('ANDROID_SMS_URL');
            $token = $this->config['token'] ?? env('ANDROID_SMS_TOKEN');

            if (!$url) {
                return ['success' => false, 'error' => 'Android SMS gateway URL not configured'];
            }

            $request = Http::acceptJson()->asJson();

            if ($token) {
                $request = $request->withToken($token);
            }

            $response = $request->post($url, [
                'phone' => $phone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                $payload = $response->json();

                if (is_array($payload) && array_key_exists('success', $payload) && !$payload['success']) {
                    return ['success' => false, 'error' => $payload['message'] ?? 'Android SMS gateway rejected the message'];
                }

                return [
                    'success' => true,
                    'provider' => 'android',
                    'message_id' => is_array($payload) ? ($payload['message_id'] ?? $payload['id'] ?? null) : null,
                ];
            }

            return ['success' => false, 'error' => $response->body()];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send via Semaphore (Philippines SMS provider)
     */
    protected function sendViaSemaphore(string $phone, string $message): array
    {
        try {
            $apiKey = $this->config['api_key'] ?? env('SEMAPHORE_API_KEY');
            $senderName = $this->config['sender_name'] ?? env('SEMAPHORE_SENDER_NAME');

            if (!$apiKey || !$senderName) {
                return ['success' => false, 'error' => 'Semaphore credentials not configured'];
            }

            $response = Http::post('https://api.semaphore.co/api/v4/messages', [
                'apikey' => $apiKey,
                'number' => $phone,
                'message' => $message,
                'sendername' => $senderName,
            ]);

            if ($response->successful()) {
                return ['success' => true, 'provider' => 'semaphore', 'message_id' => $response->json('message_id')];
            }

            return ['success' => false, 'error' => $response->body()];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Normalize Philippine phone number to international format
     */
    protected function normalizePhone(string $phone): ?string
    {
        // Remove all non-digits
        $phone = preg_replace('/\D/', '', $phone);

        if (empty($phone)) {
            return null;
        }

        // Convert 09xxxxxxxxx to +639xxxxxxxxx
        if (str_starts_with($phone, '0') && strlen($phone) === 11) {
            return '+63' . substr($phone, 1);
        }

        // Convert 9xxxxxxxxx to +639xxxxxxxxx
        if (str_starts_with($phone, '9') && strlen($phone) === 10) {
            return '+63' . $phone;
        }

        // Already has country code
        if (str_starts_with($phone, '63') && strlen($phone) === 12) {
            return '+' . $phone;
        }

        if (str_starts_with($phone, '+63') && strlen($phone) === 13) {
            return $phone;
        }

        // Return as-is if already formatted
        if (str_starts_with($phone, '+')) {
            return $phone;
        }

        return null;
    }

    /**
     * Send bulk SMS for multiple appointments
     */
    public function sendBulkReminders(array $appointments): array
    {
        $results = [
            'sent' => 0,
            'failed' => 0,
            'details' => [],
        ];

        foreach ($appointments as $appointment) {
            $result = $this->sendAppointmentReminder($appointment);
            if ($result['success']) {
                $results['sent']++;
            } else {
                $results['failed']++;
            }
            $results['details'][] = array_merge(['appointment_id' => $appointment->id], $result);
        }

        return $results;
    }
}