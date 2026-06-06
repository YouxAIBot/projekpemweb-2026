<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class WhatsAppService
{
    public function send(string $phone, string $message): bool
    {
        if (!config('services.fonnte.token')) { Log::info('WA simulated', compact('phone','message')); return true; }
        $response = Http::withHeaders(['Authorization'=>config('services.fonnte.token')])->asForm()->post('https://api.fonnte.com/send', ['target'=>$phone, 'message'=>$message]);
        return $response->successful();
    }
}
