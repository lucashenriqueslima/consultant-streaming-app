<?php
namespace App\Services\Documents\Certificates;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\{Candidate, CandidateCertificate, Course};
use App\Enums\Panel;
use Illuminate\Support\Facades\Log;

class GenerateCertificateCandidate
{
    /**
     * Gera o PDF e salva em base64 no banco de dados.
     *
     * @param Candidate $candidate
     * @return void
     */
    public function generateAndSavePdf(Candidate $candidate, Panel $namePanel): void
    {
        $candidateCertificate = CandidateCertificate::firstOrNew(
            [
                'candidate_id' => $candidate->id,
                'panel' => $namePanel->value,
            ]
        );

        $pdf = Pdf::loadView('documents.pdf.candidate-certificate', [
            'name' => $candidate->name,
        ])->setPaper('a4', 'landscape')
          ->setOption('enable-local-file-access', true)
          ->setOption('margin-top', 0)
          ->setOption('margin-right', 0)
          ->setOption('margin-bottom', 0)
          ->setOption('margin-left', 0)
          ->setOption('encoding', 'UTF-8')
          ->setOption('enable-html5-parser', true);

        Log::info("PDF generated for candidate", ['pdf' => base64_encode($pdf->output())]);

        $candidateCertificate->certificate_base64 = base64_encode($pdf->output());
        $candidateCertificate->save();
    }
}
