<?php
namespace App\Jobs;

use App\Enums\CandidateStatus;
use App\Services\PuxaCapivara\ConsultSheet;
use App\Mail\CandidateStatusActive;
use Illuminate\Support\Facades\Mail;
use App\Models\Candidate;
use App\Notifications\RegisterSendNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPuxaCapivaraJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    protected Candidate $candidate;
    private string $status;

    /**
     * Create a new job instance.
     */
    public function __construct(Candidate $candidate)
    {
        $this->candidate = $candidate;
    }

    /**
     * Execute the job.
     */
    public function handle(ConsultSheet $ConsultSheet)
    {
        try {

            Log::info(__FILE__ . " - Iniciando consulta na PuxaCapivara", ['cpf' => $this->candidate->cpf]);
            Log::info('Association: ', $this->candidate->association);

            $candidateCriminalHistory = $ConsultSheet->searchDataByDocument(
                cpf: $this->candidate->cpf,
                association: $this->candidate->association,
                timeout: 600
            );

            Log::info(__FILE__ . " - Resposta completa da consulta", [
                'cpf' => $this->candidate->cpf,
                'response' => $candidateCriminalHistory,
            ]);

            if (!isset($candidateCriminalHistory['success']) || !is_bool($candidateCriminalHistory['success'])) {
                Log::error(__FILE__ . " - Resposta inválida da consulta", ['cpf' => $this->candidate->cpf, 'response' => $candidateCriminalHistory]);
                throw new \Exception('Erro ao processar a consulta da capivara, chave "success" não encontrada ou inválida');
            }

            $this->status = ($candidateCriminalHistory['success'])
                ? CandidateStatus::ACTIVE->value
                : CandidateStatus::REFUSED_BY_CRIMINAL_HISTORY->value;

            $this->candidate->update(['status' => $this->status]);
            $this->candidate->notify(new RegisterSendNotifications($this->candidate));

        } catch (\Throwable $th) {
            Log::error(__FILE__ . " - Erro ao consultar no PuxaCapivara", [
                'cpf' => $this->candidate->cpf,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
            $this->release(660);
        }
    }
}

