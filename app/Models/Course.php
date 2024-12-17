<?php

namespace App\Models;

use App\Enums\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'panel' => Panel::class
    ];


    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }
}
