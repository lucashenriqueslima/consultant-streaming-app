<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class CreateAccessLogJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected User $user,
        protected string $ip,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $cityAndState = $this->getCityAndStateByIp($this->ip);

        if ($cityAndState['status'] === 'fail') {
            return;
        }

        $this->user->accessLogs()->create([
            'ip_address' => $this->ip,
            'city' => $cityAndState['city'],
            'state' => $cityAndState['state'],
        ]);
    }

    protected function getCityAndStateByIp(string $ip): array
    {
        $response = Http::get("http://ip-api.com/json/{$ip}");

        $json = $response->json();
        return [
            'status' => $json['status'],
            'city' => $json['city'],
            'state' => $json['regionName'],
        ];
    }
}
