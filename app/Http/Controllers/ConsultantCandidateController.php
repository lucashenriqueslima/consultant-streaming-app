<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessPuxaCapivaraJob;
use App\Models\Candidate;
use Illuminate\Support\Facades\Log;

class ConsultantCandidateController extends Controller
{
    public function beginConsultApiPuxaCapivara(Request $request)
    {
        $cpf = $request->cpf;
        $candidate = Candidate::where('cpf', $cpf)->first();

        if (!$candidate) {
            Log::error('Candidato não encontrado', ['cpf' => $cpf]);
            return response()->json(['status' => 'Candidato não encontrado'], 404);
        }

        ProcessPuxaCapivaraJob::dispatch($candidate);

        Log::info('Job iniciado', ['cpf' => $cpf]);
        return response()->json(['status' => 'Job iniciado']);
    }
}
