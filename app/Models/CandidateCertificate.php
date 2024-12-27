<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateCertificate extends Model
{
    use HasFactory;

    protected $table = 'candidate_certificates';

    protected $fillable = [
        'candidate_id',
        'certificate_base64',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
