<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class WhatsAppService
{
    public function send(string $phone, string $message): bool
    {
        if (!config('services.fonnte.token')) {
            Log::info('WA simulated', compact('phone','message'));
            return true;
        }

        try {
            $response = Http::withHeaders(['Authorization' => config('services.fonnte.token')])
                ->asForm()
                ->post('https://api.fonnte.com/send', ['target' => $phone, 'message' => $message]);
        } catch (\Throwable $exception) {
            Log::error('WA send failed', [
                'phone' => $phone,
                'message' => $message,
                'error' => $exception->getMessage(),
            ]);
            return false;
        }

        if (! $response->successful()) {
            Log::error('WA send failed', [
                'phone' => $phone,
                'message' => $message,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        return $response->successful();
    }
}
