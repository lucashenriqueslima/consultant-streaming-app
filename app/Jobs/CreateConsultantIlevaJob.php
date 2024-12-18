<?php

namespace App\Jobs;

use App\DTOs\IlevaConsultantDTO;
use App\Services\Ileva\RegisterConsultant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateConsultantIlevaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $candidate;

    public function __construct($candidate)
    {
        $this->candidate = $candidate;
    }

    public function handle()
    {
        $registerConsultant = new RegisterConsultant();
        $response = $registerConsultant->execute(
            new IlevaConsultantDTO(
                status:  $this->candidate->status->value,
                name:  $this->candidate->name,
                email:  $this->candidate->email,
                phone:  $this->candidate->phone,
                cpf:  $this->candidate->cpf,
                team_code:  $this->candidate->ileva_team_id,
                association: $this->candidate->association,
            )
        );

        if ($response->successful()) {
            Log::info("Consultor {$this->candidate->email} criado com sucesso no iLeva.");
        } else {
            Log::warning("Falha ao criar consultor {$this->candidate->email} no iLeva. Re-tentando...");
            $this->release(60);
        }
    }
}
