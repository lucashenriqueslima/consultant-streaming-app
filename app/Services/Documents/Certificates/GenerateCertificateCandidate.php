<?php
namespace App\Services\Documents\Certificates;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Candidate;
use App\Models\CandidateCertificate;
use Illuminate\Support\Facades\Log;

class GenerateCertificateCandidate
{
    /**
     * Gera o PDF e salva em base64 no banco de dados.
     *
     * @param Candidate $candidate
     * @return void
     */
    public function generateAndSavePdf(Candidate $candidate): void
    {
        $candidateCertificate = CandidateCertificate::firstOrNew(
            ['candidate_id' => $candidate->id]
        );

        $pdf = Pdf::loadView('documents.pdf.candidate-certificate', [
            'name' => $candidate->name,
        ]);
        $pdf->setPaper('a4', 'landscape');

        Log::info("message", ['pdf' => base64_encode($pdf->output())]);

        $candidateCertificate->certificate_base64 = base64_encode($pdf->output());
        $candidateCertificate->save();
    }
}
