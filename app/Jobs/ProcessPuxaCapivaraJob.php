<?php
namespace App\Jobs;

use App\Enums\CandidateStatus;
use App\Services\PuxaCapivara\ConsultSheet;
use App\Mail\CandidateStatusActive;
use Illuminate\Support\Facades\Mail;
use App\Models\Candidate;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPuxaCapivaraJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    protected $candidate;
    private string $status = CandidateStatus::PENDING_REGISTRATION;

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
            $candidateCriminalHistory = $ConsultSheet->searchDataByDocument($this->candidate->cpf, timeout: 999);

            if (!isset($candidateCriminalHistory['success'])) {
                throw new \Exception('Erro ao processar a consulta da capivara, chave "success" não encontrada');
            }

            $this->status = ($candidateCriminalHistory['success'])
                ? CandidateStatus::ACTIVE
                : CandidateStatus::REFUSED_BY_CRIMINAL_HISTORY;

            $this->candidate->update(['status' => $this->status]);

            Mail::to($this->candidate->email)->send(new CandidateStatusActive($this->candidate->name));
        } catch (\Throwable $th) {
            Log::error("Erro ao processar a consulta da capivara para o candidato {$this->candidate->name} - {$th->getMessage()}");
            $this->release(60);
        }
    }
}

