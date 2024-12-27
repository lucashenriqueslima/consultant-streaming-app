<?php

namespace App\Livewire\Candidate;

use Livewire\Component;
use App\Models\CandidateCertificate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class Certificates extends Component
{
    public Collection $certificates;

    public function mount()
    {
        $this->certificates = CandidateCertificate::where('candidate_id', Auth::guard('candidate')->id())->get();
    }

    public function download(CandidateCertificate $certificate)
    {
        return response()->streamDownload(function () use ($certificate) {
            echo base64_decode($certificate->certificate_base64);
        }, 'certificate-growthflix.pdf');
    }

    public function render()
    {
        return view('livewire.candidate.certificates');
    }
}
