<?php

namespace App\Models\IlevaSolidy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultantIlevaSolidy extends Model
{
    use HasFactory;

    protected $connection = 'ileva_solidy';
    protected $table = 'hbrd_adm_consultant';
}
