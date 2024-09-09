<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pages;

class Galleries extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'path',
        'image'
    ];

    public function page()
    {
        return $this->belongsTo(Pages::class, 'parent_id', 'id');
    }
}
